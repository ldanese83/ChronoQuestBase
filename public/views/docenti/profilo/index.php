<?php

use App\Service\TranslationService;

$translator = new TranslationService();
$profile = $profile ?? [];
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?= $translator->translate('profile.edit.title') ?></h6>
                </div>
                <div class="card-body">
                    <p class="mb-4"><?= $translator->translate('profile.teacher.description') ?></p>

                    <form method="POST" action="/docenti/profilo">
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $translator->translate('profile.full_name') ?></label>
                            <input
                                type="text"
                                class="form-control"
                                value="<?= htmlspecialchars(((string) ($profile['nome'] ?? '')) . ' ' . ((string) ($profile['cognome'] ?? ''))) ?>"
                                disabled
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $translator->translate('profile.username') ?></label>
                            <input
                                type="text"
                                class="form-control"
                                value="<?= htmlspecialchars((string) ($profile['username'] ?? '')) ?>"
                                disabled
                            >
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold"><?= $translator->translate('profile.email') ?></label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                required
                                value="<?= htmlspecialchars((string) ($profile['email'] ?? '')) ?>"
                            >
                        </div>

                        <div class="mb-3">
                            <label for="receive_mail" class="form-label fw-bold"><?= $translator->translate('profile.receive_mail') ?></label>
                            <select class="form-select" id="receive_mail" name="receive_mail">
                                <option value="0" <?= ((int) ($profile['ricevi_mail'] ?? 0) === 0) ? 'selected' : '' ?>><?= $translator->translate('profile.no') ?></option>
                                <option value="1" <?= ((int) ($profile['ricevi_mail'] ?? 0) === 1) ? 'selected' : '' ?>><?= $translator->translate('profile.yes') ?></option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="api_gemini" class="form-label fw-bold"><?= $translator->translate('profile.gemini_api_key') ?></label>
                            <input
                                type="text"
                                class="form-control"
                                id="api_gemini"
                                name="api_gemini"
                                value="<?= htmlspecialchars((string) ($profile['API_gemini'] ?? '')) ?>"
                            >
                        </div>

                        <div class="mb-3">
                            <label for="language" class="form-label fw-bold"><?= $translator->translate('profile.language') ?></label>
                            <select class="form-select" id="language" name="language">
                                <option value="en" <?= ((string) ($profile['language'] ?? 'en') === 'en') ? 'selected' : '' ?>><?= $translator->translate('profile.language.english_default') ?></option>
                                <option value="it" <?= ((string) ($profile['language'] ?? 'en') === 'it') ? 'selected' : '' ?>><?= $translator->translate('profile.language.italian') ?></option>
                            </select>
                        </div>

                        <hr>
                        <p class="mb-3"><strong><?= $translator->translate('profile.password') ?></strong> <?= $translator->translate('profile.password.leave_empty') ?></p>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold"><?= $translator->translate('profile.password.new') ?></label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>

                        <div class="mb-4">
                            <label for="password_confirm" class="form-label fw-bold"><?= $translator->translate('profile.password.confirm') ?></label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm">
                        </div>

                        <button type="submit" class="btn btn-primary"><?= $translator->translate('profile.save_changes') ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
