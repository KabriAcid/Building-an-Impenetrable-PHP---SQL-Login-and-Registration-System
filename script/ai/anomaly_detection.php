<?php
// I implemented AI-based anomaly detection
require '../vendor/autoload.php';
use Phpml\AnomalyDetection\GaussianMixture;

function detect_anomalies($login_attempts) {
    $gmm = new GaussianMixture();
    $gmm->train($login_attempts);

    $anomalies = [];
    foreach ($login_attempts as $attempt) {
        if ($gmm->predict($attempt) == 1) {
            $anomalies[] = $attempt;
        }
    }
    return $anomalies;
}
?>
