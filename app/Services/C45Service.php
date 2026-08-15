<?php

namespace App\Services;

use App\Models\DatasetTelur;
use Illuminate\Support\Collection;

class C45Service
{
    /**
     * Calculate entropy of a set given counts of classes.
     */
    public function calculateEntropy(int $total, int $layakCount, int $tidakLayakCount): float
    {
        if ($total === 0 || $layakCount === 0 || $tidakLayakCount === 0) {
            return 0.0;
        }

        $p1 = $layakCount / $total;
        $p2 = $tidakLayakCount / $total;

        return - ($p1 * log($p1, 2) + $p2 * log($p2, 2));
    }

    /**
     * Get detailed step-by-step C4.5 calculations for presentation in UI.
     */
    public function getCalculationDetails(?Collection $dataset = null): array
    {
        if ($dataset === null) {
            $dataset = DatasetTelur::all();
        }

        $totalCount = $dataset->count();
        $layakCount = $dataset->where('kualitas', 'Layak Jual')->count();
        $tidakLayakCount = $dataset->where('kualitas', 'Tidak Layak Jual')->count();

        $initialEntropy = $this->calculateEntropy($totalCount, $layakCount, $tidakLayakCount);

        // 1. Continuous Attribute: Berat
        $beratAnalysis = $this->analyzeContinuousAttribute($dataset, 'berat', $initialEntropy);

        // 2. Continuous Attribute: Diameter
        $diameterAnalysis = $this->analyzeContinuousAttribute($dataset, 'diameter', $initialEntropy);

        // 3. Categorical Attribute: Kondisi Cangkang
        $kondisiAnalysis = $this->analyzeCategoricalAttribute($dataset, 'kondisi_cangkang', $initialEntropy);

        // 4. Categorical Attribute: Warna Cangkang
        $warnaAnalysis = $this->analyzeCategoricalAttribute($dataset, 'warna_cangkang', $initialEntropy);

        // Summary Gain Comparison
        $summaryGains = [
            [
                'attribute' => 'Berat Telur',
                'best_split' => '≤ ' . number_format($beratAnalysis['best_threshold'], 2) . ' Gram',
                'gain' => $beratAnalysis['best_gain'],
                'split_info' => $beratAnalysis['best_split_info'],
                'gain_ratio' => $beratAnalysis['best_gain_ratio'],
            ],
            [
                'attribute' => 'Diameter Telur',
                'best_split' => '≤ ' . number_format($diameterAnalysis['best_threshold'], 2) . ' Cm',
                'gain' => $diameterAnalysis['best_gain'],
                'split_info' => $diameterAnalysis['best_split_info'],
                'gain_ratio' => $diameterAnalysis['best_gain_ratio'],
            ],
            [
                'attribute' => 'Kondisi Cangkang',
                'best_split' => 'Kategori (Normal / Retak)',
                'gain' => $kondisiAnalysis['gain'],
                'split_info' => $kondisiAnalysis['split_info'],
                'gain_ratio' => $kondisiAnalysis['gain_ratio'],
            ],
            [
                'attribute' => 'Warna Cangkang',
                'best_split' => 'Kategori (Cokelat Tua / Cokelat Muda)',
                'gain' => $warnaAnalysis['gain'],
                'split_info' => $warnaAnalysis['split_info'],
                'gain_ratio' => $warnaAnalysis['gain_ratio'],
            ],
        ];

        // Sort by Gain descending
        usort($summaryGains, fn($a, $b) => $b['gain'] <=> $a['gain']);

        // Build Decision Tree
        $tree = $this->buildTree($dataset);

        // Extract Rules
        $rules = $this->extractRules($tree);

        // Evaluate Confusion Matrix
        $evaluation = $this->evaluateConfusionMatrix($dataset, $tree);

        return [
            'total_count' => $totalCount,
            'layak_count' => $layakCount,
            'tidak_layak_count' => $tidakLayakCount,
            'initial_entropy' => $initialEntropy,
            'berat_analysis' => $beratAnalysis,
            'diameter_analysis' => $diameterAnalysis,
            'kondisi_analysis' => $kondisiAnalysis,
            'warna_analysis' => $warnaAnalysis,
            'summary_gains' => $summaryGains,
            'best_root' => $summaryGains[0],
            'tree' => $tree,
            'rules' => $rules,
            'evaluation' => $evaluation,
        ];
    }

    /**
     * Analyze continuous attribute (Berat or Diameter) finding all midpoints and max gain threshold.
     */
    protected function analyzeContinuousAttribute(Collection $dataset, string $field, float $initialEntropy): array
    {
        $values = $dataset->pluck($field)->map(fn($v) => (float) $v)->unique()->sort()->values();
        $total = $dataset->count();

        $candidates = [];
        $bestThreshold = 0;
        $bestGain = -1;
        $bestSplitInfo = 0;
        $bestGainRatio = 0;

        for ($i = 0; $i < count($values) - 1; $i++) {
            $threshold = ($values[$i] + $values[$i + 1]) / 2.0;

            $left = $dataset->filter(fn($item) => (float) $item->$field <= $threshold);
            $right = $dataset->filter(fn($item) => (float) $item->$field > $threshold);

            $leftTotal = $left->count();
            $leftLayak = $left->where('kualitas', 'Layak Jual')->count();
            $leftTidak = $left->where('kualitas', 'Tidak Layak Jual')->count();
            $leftEntropy = $this->calculateEntropy($leftTotal, $leftLayak, $leftTidak);

            $rightTotal = $right->count();
            $rightLayak = $right->where('kualitas', 'Layak Jual')->count();
            $rightTidak = $right->where('kualitas', 'Tidak Layak Jual')->count();
            $rightEntropy = $this->calculateEntropy($rightTotal, $rightLayak, $rightTidak);

            $weightedEntropy = ($leftTotal / $total * $leftEntropy) + ($rightTotal / $total * $rightEntropy);
            $gain = $initialEntropy - $weightedEntropy;

            $pLeft = $leftTotal / $total;
            $pRight = $rightTotal / $total;
            $splitInfo = 0;
            if ($pLeft > 0) $splitInfo -= $pLeft * log($pLeft, 2);
            if ($pRight > 0) $splitInfo -= $pRight * log($pRight, 2);

            $gainRatio = $splitInfo > 0 ? $gain / $splitInfo : 0;

            $candidates[] = [
                'val_a' => $values[$i],
                'val_b' => $values[$i + 1],
                'threshold' => $threshold,
                'left_count' => $leftTotal,
                'left_layak' => $leftLayak,
                'left_tidak' => $leftTidak,
                'left_entropy' => $leftEntropy,
                'right_count' => $rightTotal,
                'right_layak' => $rightLayak,
                'right_tidak' => $rightTidak,
                'right_entropy' => $rightEntropy,
                'weighted_entropy' => $weightedEntropy,
                'gain' => $gain,
                'split_info' => $splitInfo,
                'gain_ratio' => $gainRatio,
            ];

            if ($gain > $bestGain) {
                $bestGain = $gain;
                $bestThreshold = $threshold;
                $bestSplitInfo = $splitInfo;
                $bestGainRatio = $gainRatio;
            }
        }

        return [
            'field' => $field,
            'candidates' => $candidates,
            'best_threshold' => $bestThreshold,
            'best_gain' => $bestGain,
            'best_split_info' => $bestSplitInfo,
            'best_gain_ratio' => $bestGainRatio,
        ];
    }

    /**
     * Analyze categorical attribute (Kondisi Cangkang or Warna Cangkang).
     */
    protected function analyzeCategoricalAttribute(Collection $dataset, string $field, float $initialEntropy): array
    {
        $total = $dataset->count();
        $grouped = $dataset->groupBy($field);

        $valuesDetails = [];
        $weightedEntropy = 0.0;
        $splitInfo = 0.0;

        foreach ($grouped as $val => $items) {
            $count = $items->count();
            $layak = $items->where('kualitas', 'Layak Jual')->count();
            $tidak = $items->where('kualitas', 'Tidak Layak Jual')->count();
            $entropy = $this->calculateEntropy($count, $layak, $tidak);

            $weightedEntropy += ($count / $total) * $entropy;

            $p = $count / $total;
            if ($p > 0) {
                $splitInfo -= $p * log($p, 2);
            }

            $valuesDetails[$val] = [
                'value' => $val,
                'count' => $count,
                'layak' => $layak,
                'tidak' => $tidak,
                'entropy' => $entropy,
            ];
        }

        $gain = $initialEntropy - $weightedEntropy;
        $gainRatio = $splitInfo > 0 ? $gain / $splitInfo : 0;

        return [
            'field' => $field,
            'details' => $valuesDetails,
            'weighted_entropy' => $weightedEntropy,
            'gain' => $gain,
            'split_info' => $splitInfo,
            'gain_ratio' => $gainRatio,
        ];
    }

    /**
     * Recursively build decision tree node.
     */
    public function buildTree(Collection $dataset, array $availableAttributes = ['berat', 'diameter', 'kondisi_cangkang', 'warna_cangkang'], int $depth = 0): array
    {
        $total = $dataset->count();
        $layakCount = $dataset->where('kualitas', 'Layak Jual')->count();
        $tidakLayakCount = $dataset->where('kualitas', 'Tidak Layak Jual')->count();

        // Base Case 1: Pure leaf
        if ($total === 0) {
            return ['type' => 'leaf', 'label' => 'Layak Jual', 'samples' => 0, 'confidence' => 0];
        }
        if ($layakCount === $total) {
            return ['type' => 'leaf', 'label' => 'Layak Jual', 'samples' => $total, 'layak' => $total, 'tidak' => 0];
        }
        if ($tidakLayakCount === $total) {
            return ['type' => 'leaf', 'label' => 'Tidak Layak Jual', 'samples' => $total, 'layak' => 0, 'tidak' => $total];
        }
        if (empty($availableAttributes) || $depth > 5) {
            $label = $layakCount >= $tidakLayakCount ? 'Layak Jual' : 'Tidak Layak Jual';
            return ['type' => 'leaf', 'label' => $label, 'samples' => $total, 'layak' => $layakCount, 'tidak' => $tidakLayakCount];
        }

        $initialEntropy = $this->calculateEntropy($total, $layakCount, $tidakLayakCount);

        // Evaluate all available attributes
        $bestAttr = null;
        $maxGain = -1;
        $bestDetails = null;

        foreach ($availableAttributes as $attr) {
            if (in_array($attr, ['berat', 'diameter'])) {
                $res = $this->analyzeContinuousAttribute($dataset, $attr, $initialEntropy);
                if ($res['best_gain'] > $maxGain) {
                    $maxGain = $res['best_gain'];
                    $bestAttr = $attr;
                    $bestDetails = $res;
                }
            } else {
                $res = $this->analyzeCategoricalAttribute($dataset, $attr, $initialEntropy);
                if ($res['gain'] > $maxGain) {
                    $maxGain = $res['gain'];
                    $bestAttr = $attr;
                    $bestDetails = $res;
                }
            }
        }

        if ($bestAttr === null || $maxGain <= 0.0001) {
            $label = $layakCount >= $tidakLayakCount ? 'Layak Jual' : 'Tidak Layak Jual';
            return ['type' => 'leaf', 'label' => $label, 'samples' => $total, 'layak' => $layakCount, 'tidak' => $tidakLayakCount];
        }

        $node = [
            'type' => 'node',
            'attribute' => $bestAttr,
            'gain' => $maxGain,
            'samples' => $total,
            'entropy' => $initialEntropy,
            'children' => [],
        ];

        $nextAttrs = array_values(array_filter($availableAttributes, fn($a) => $a !== $bestAttr));

        if (in_array($bestAttr, ['berat', 'diameter'])) {
            $threshold = $bestDetails['best_threshold'];
            $node['threshold'] = $threshold;

            $leftSubset = $dataset->filter(fn($i) => (float) $i->$bestAttr <= $threshold);
            $rightSubset = $dataset->filter(fn($i) => (float) $i->$bestAttr > $threshold);

            $node['children']["<= {$threshold}"] = $this->buildTree($leftSubset, $nextAttrs, $depth + 1);
            $node['children']["> {$threshold}"] = $this->buildTree($rightSubset, $nextAttrs, $depth + 1);
        } else {
            foreach ($bestDetails['details'] as $val => $info) {
                $subset = $dataset->filter(fn($i) => $i->$bestAttr === $val);
                $node['children'][$val] = $this->buildTree($subset, $nextAttrs, $depth + 1);
            }
        }

        return $node;
    }

    /**
     * Extract rules from decision tree.
     */
    public function extractRules(array $tree, array $currentConditions = []): array
    {
        $rules = [];

        if ($tree['type'] === 'leaf') {
            $ruleStr = empty($currentConditions) ? "IF TRUE" : "IF " . implode(" AND ", $currentConditions);
            $ruleStr .= " THEN Kualitas = " . $tree['label'];
            return [
                [
                    'conditions' => $currentConditions,
                    'label' => $tree['label'],
                    'rule_text' => $ruleStr,
                    'samples' => $tree['samples'] ?? 0,
                ]
            ];
        }

        foreach ($tree['children'] as $branchVal => $childNode) {
            $attrName = match($tree['attribute']) {
                'berat' => 'Berat Telur',
                'diameter' => 'Diameter Telur',
                'kondisi_cangkang' => 'Kondisi Cangkang',
                'warna_cangkang' => 'Warna Cangkang',
                default => $tree['attribute'],
            };

            $condition = str_starts_with($branchVal, '<=') || str_starts_with($branchVal, '>')
                ? "{$attrName} {$branchVal}"
                : "{$attrName} = {$branchVal}";

            $childRules = $this->extractRules($childNode, array_merge($currentConditions, [$condition]));
            $rules = array_merge($rules, $childRules);
        }

        return $rules;
    }

    /**
     * Classify an egg sample based on tree or rule evaluation.
     */
    public function classify(float $berat, float $diameter, string $kondisiCangkang, string $warnaCangkang, ?array $tree = null): array
    {
        if ($tree === null) {
            $dataset = DatasetTelur::all();
            $tree = $this->buildTree($dataset);
        }

        $currentNode = $tree;
        $appliedConditions = [];

        while ($currentNode['type'] === 'node') {
            $attr = $currentNode['attribute'];

            if ($attr === 'berat') {
                $threshold = $currentNode['threshold'];
                if ($berat <= $threshold) {
                    $appliedConditions[] = "Berat Telur ≤ {$threshold} Gram";
                    $currentNode = $currentNode['children']["<= {$threshold}"];
                } else {
                    $appliedConditions[] = "Berat Telur > {$threshold} Gram";
                    $currentNode = $currentNode['children']["> {$threshold}"];
                }
            } elseif ($attr === 'diameter') {
                $threshold = $currentNode['threshold'];
                if ($diameter <= $threshold) {
                    $appliedConditions[] = "Diameter Telur ≤ {$threshold} Cm";
                    $currentNode = $currentNode['children']["<= {$threshold}"];
                } else {
                    $appliedConditions[] = "Diameter Telur > {$threshold} Cm";
                    $currentNode = $currentNode['children']["> {$threshold}"];
                }
            } elseif ($attr === 'kondisi_cangkang') {
                $val = $kondisiCangkang;
                $appliedConditions[] = "Kondisi Cangkang = {$val}";
                if (isset($currentNode['children'][$val])) {
                    $currentNode = $currentNode['children'][$val];
                } else {
                    // Fallback to first child or default
                    $firstKey = array_key_first($currentNode['children']);
                    $currentNode = $currentNode['children'][$firstKey];
                }
            } elseif ($attr === 'warna_cangkang') {
                $val = $warnaCangkang;
                $appliedConditions[] = "Warna Cangkang = {$val}";
                if (isset($currentNode['children'][$val])) {
                    $currentNode = $currentNode['children'][$val];
                } else {
                    $firstKey = array_key_first($currentNode['children']);
                    $currentNode = $currentNode['children'][$firstKey];
                }
            }
        }

        $ruleStr = empty($appliedConditions) ? "IF ALL SAMPLES" : "IF " . implode(" AND ", $appliedConditions);
        $ruleStr .= " THEN " . $currentNode['label'];

        return [
            'label' => $currentNode['label'],
            'rule_applied' => $ruleStr,
            'confidence' => 100.0,
        ];
    }

    /**
     * Evaluate model performance with Confusion Matrix.
     */
    public function evaluateConfusionMatrix(Collection $dataset, ?array $tree = null): array
    {
        if ($tree === null) {
            $tree = $this->buildTree($dataset);
        }

        $tp = 0; // Actual Layak, Predicted Layak
        $fn = 0; // Actual Layak, Predicted Tidak Layak
        $fp = 0; // Actual Tidak Layak, Predicted Layak
        $tn = 0; // Actual Tidak Layak, Predicted Tidak Layak

        $details = [];

        foreach ($dataset as $row) {
            $actual = $row->kualitas;
            $res = $this->classify((float) $row->berat, (float) $row->diameter, $row->kondisi_cangkang, $row->warna_cangkang, $tree);
            $pred = $res['label'];
            $isCorrect = ($actual === $pred);

            if ($actual === 'Layak Jual' && $pred === 'Layak Jual') $tp++;
            elseif ($actual === 'Layak Jual' && $pred === 'Tidak Layak Jual') $fn++;
            elseif ($actual === 'Tidak Layak Jual' && $pred === 'Layak Jual') $fp++;
            elseif ($actual === 'Tidak Layak Jual' && $pred === 'Tidak Layak Jual') $tn++;

            $details[] = [
                'kode_telur' => $row->kode_telur,
                'berat' => $row->berat,
                'diameter' => $row->diameter,
                'kondisi_cangkang' => $row->kondisi_cangkang,
                'warna_cangkang' => $row->warna_cangkang,
                'actual' => $actual,
                'predicted' => $pred,
                'is_correct' => $isCorrect,
            ];
        }

        $total = $dataset->count();
        $accuracy = $total > 0 ? (($tp + $tn) / $total) * 100 : 0;
        $precision = ($tp + $fp) > 0 ? ($tp / ($tp + $fp)) * 100 : 0;
        $recall = ($tp + $fn) > 0 ? ($tp / ($tp + $fn)) * 100 : 0;
        $specificity = ($tn + $fp) > 0 ? ($tn / ($tn + $fp)) * 100 : 0;
        $f1Score = ($precision + $recall) > 0 ? 2 * (($precision * $recall) / ($precision + $recall)) : 0;

        return [
            'tp' => $tp,
            'fn' => $fn,
            'fp' => $fp,
            'tn' => $tn,
            'total' => $total,
            'accuracy' => round($accuracy, 2),
            'precision' => round($precision, 2),
            'recall' => round($recall, 2),
            'specificity' => round($specificity, 2),
            'f1_score' => round($f1Score, 2),
            'details' => $details,
        ];
    }
}
