<?php

/**
 * AR Entertainment - Universal FAQ Accordion Component
 * 
 * Renders an accessible, responsive FAQ accordion and injects Schema.org FAQPage JSON-LD.
 * 
 * Usage:
 * $faq_items = [
 *     ['question' => 'What film fixer services do you offer in Bangladesh?', 'answer' => 'We provide filming permits, location scouting, crew, equipment rental, and visa coordination.'],
 *     ['question' => 'How much does OVC production cost?', 'answer' => 'Packages start from BDT 80,000 for AI/stock-based productions.']
 * ];
 * include INCLUDES_PATH . '/faq-accordion.php';
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/helpers.php';

// Normalize FAQ data
$faqs = $faq_items ?? [];
if (is_string($faqs)) {
    $decoded = json_decode($faqs, true);
    $faqs = is_array($decoded) ? $decoded : [];
}

$accordion_title    = $faq_title ?? 'Frequently Asked Questions';
$accordion_subtitle = $faq_subtitle ?? 'Get quick answers to common questions about video production, filming permits, and services in Bangladesh.';
$accordion_id       = $faq_id ?? 'faqAccordion_' . substr(md5(uniqid('', true)), 0, 8);
$output_schema      = $faq_schema ?? true;

if (!empty($faqs)):
    $schema_questions = [];
?>
<section class="ar-faq-section py-5 my-4" id="<?= htmlspecialchars($accordion_id) ?>_sec">
    <div class="container">
        <!-- Section Header -->
        <div class="text-center mb-5">
            <div class="d-inline-flex align-items-center mb-2 px-3 py-1 rounded-pill" style="background: rgba(229, 9, 20, 0.1); border: 1px solid rgba(229, 9, 20, 0.3); color: #e50914; font-size: 12px; font-weight: 700; letter-spacing: 1px;">
                <i class="fa-solid fa-circle-question mr-2"></i> FAQS &amp; GUIDES
            </div>
            <h2 class="h2 text-white font-weight-bold" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                <?= htmlspecialchars($accordion_title) ?>
            </h2>
            <?php if (!empty($accordion_subtitle)): ?>
                <p class="text-muted mx-auto" style="max-width: 640px; color: #9ca3af !important; font-size: 15px;">
                    <?= htmlspecialchars($accordion_subtitle) ?>
                </p>
            <?php endif; ?>
        </div>

        <!-- Accordion Container -->
        <div class="accordion" id="<?= htmlspecialchars($accordion_id) ?>">
            <?php foreach ($faqs as $index => $item): 
                $question = $item['question'] ?? $item['q'] ?? '';
                $answer   = $item['answer'] ?? $item['a'] ?? '';
                if (empty($question) || empty($answer)) continue;

                $item_id = $accordion_id . '_item_' . $index;
                $is_first = ($index === 0);

                if ($output_schema) {
                    $schema_questions[] = [
                        "@type" => "Question",
                        "name" => strip_tags($question),
                        "acceptedAnswer" => [
                            "@type" => "Answer",
                            "text" => strip_tags($answer)
                        ]
                    ];
                }
            ?>
                <div class="card mb-3" style="background: #14151f; border: 1px solid #242638; border-radius: 8px; overflow: hidden;">
                    <div class="card-header p-0" id="heading_<?= htmlspecialchars($item_id) ?>" style="background: transparent; border: none;">
                        <button class="btn btn-link btn-block text-left py-3 px-4 d-flex justify-content-between align-items-center <?= $is_first ? '' : 'collapsed' ?>"
                                type="button"
                                data-toggle="collapse"
                                data-target="#collapse_<?= htmlspecialchars($item_id) ?>"
                                aria-expanded="<?= $is_first ? 'true' : 'false' ?>"
                                aria-controls="collapse_<?= htmlspecialchars($item_id) ?>"
                                style="color: #ffffff; text-decoration: none; font-weight: 600; font-size: 16px; font-family: 'Plus Jakarta Sans', sans-serif;">
                            <span><?= htmlspecialchars($question) ?></span>
                            <i class="fa-solid fa-chevron-down ml-3 transition-transform" style="font-size: 14px; color: #e50914;"></i>
                        </button>
                    </div>

                    <div id="collapse_<?= htmlspecialchars($item_id) ?>"
                         class="collapse <?= $is_first ? 'show' : '' ?>"
                         aria-labelledby="heading_<?= htmlspecialchars($item_id) ?>"
                         data-parent="#<?= htmlspecialchars($accordion_id) ?>">
                        <div class="card-body px-4 pb-4 pt-1" style="color: #cbd5e1; font-size: 15px; line-height: 1.7; border-top: 1px solid rgba(255,255,255,0.05);">
                            <?= $answer ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- FAQPage Schema JSON-LD -->
<?php if ($output_schema && !empty($schema_questions)): ?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": <?= json_encode($schema_questions, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
}
</script>
<?php endif; ?>
<?php endif; ?>
