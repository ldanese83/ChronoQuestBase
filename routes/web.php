<?php

use App\Controller\AuthController;
use App\Controller\DashboardDocentiController;
use App\Controller\TeacherClassController;
use App\Controller\StudentDashboardController;
use App\Controller\StudentCommunicationsController;
use App\Controller\StudentManagementController;
use App\Controller\StudentPowersController;
use App\Controller\StudentTeamsController;
use App\Controller\StudentQuestController;
use App\Controller\StudentChestsController;
use App\Controller\StudentBadgesController;
use App\Controller\StudentPunishmentsController;
use App\Controller\StudentCustomizationController;
use App\Controller\StudentProfileController;
use App\Controller\TeacherCommunicationsController;
use App\Controller\TeacherManagementController;
use App\Controller\TeacherTeamsController;
use App\Controller\TeacherQuestController;
use App\Controller\TeacherBadgesController;
use App\Controller\TeacherPowersController;
use App\Controller\TeacherCharacterController;
use App\Controller\TeacherPunishmentsController;
use App\Controller\TeacherMaterialsController;
use App\Controller\TeacherCustomizationController;
use App\Controller\TeacherProfileController;
use App\Controller\TestCreatorBeginController;
use App\Controller\TeacherPluginsController;
use App\Controller\PluginPageController;
use App\Service\PluginRouteRegistrar;

// login e logout
$router->get('/', function (): void {
    header('Location: /loginDoc');
    exit;
});
$router->get('/loginDoc', [AuthController::class, 'showLoginDoc']);
$router->post('/loginDoc', [AuthController::class, 'loginDoc']);
$router->get('/loginStud', [AuthController::class, 'showLoginStud']);
$router->post('/loginStud', [AuthController::class, 'loginStud']);
$router->get('/logout', [AuthController::class, 'logoutDoc']);
$router->get('/logoutStud', [AuthController::class, 'logoutStud']);

// registrazione docente
$router->get('/registrazioneDoc', [AuthController::class, 'showRegistrazioneDoc']);
$router->post('/registrazioneDoc', [AuthController::class, 'registerTeacher']);
$router->get('/validate-username', [AuthController::class, 'validateUsername']);
$router->get('/validate-email', [AuthController::class, 'validateEmail']);
$router->get('/validate-user', [AuthController::class, 'validateUser']);

//dashboard docenti
$router->get('/docenti/dashboard', [DashboardDocentiController::class, 'showDashboardDocenti']);
$router->post('/docenti/dashboard/togli-cuore', [DashboardDocentiController::class, 'removeHeart']);
$router->post('/docenti/dashboard/togli-cuore-multiplo', [DashboardDocentiController::class, 'removeHeartBulk']);
$router->post('/docenti/dashboard/morte-istantanea', [DashboardDocentiController::class, 'instantDeath']);
$router->post('/docenti/dashboard/ricompensa-multipla', [DashboardDocentiController::class, 'rewardBulk']);
$router->post('/docenti/dashboard/assegna-punizione-morte', [DashboardDocentiController::class, 'assignDeathPunishment']);

// gestione studenti docenti
$router->get('/docenti/studenti', [StudentManagementController::class, 'index']);
$router->post('/docenti/studenti/save', [StudentManagementController::class, 'store']);
$router->post('/docenti/studenti/import/csv', [StudentManagementController::class, 'importCsv']);
$router->post('/docenti/studenti/import/class', [StudentManagementController::class, 'importFromClass']);
$router->post('/docenti/studenti/{id}/delete', [StudentManagementController::class, 'delete']);

// gestione docenti della classe
$router->get('/docenti/docenti', [TeacherManagementController::class, 'index']);
$router->post('/docenti/docenti/add', [TeacherManagementController::class, 'add']);

// gestione squadre docenti/studenti unificata
$router->get('/docenti/squadre', [TeacherTeamsController::class, 'index']);
$router->post('/docenti/squadre/save', [TeacherTeamsController::class, 'save']);
$router->post('/docenti/squadre/{id}/delete', [TeacherTeamsController::class, 'delete']);
$router->post('/docenti/squadre/{id}/approve', [TeacherTeamsController::class, 'approve']);
$router->post('/docenti/squadre/{id}/reject', [TeacherTeamsController::class, 'reject']);



// gestione badge docenti
$router->get('/docenti/badge', [TeacherBadgesController::class, 'index']);
$router->get('/docenti/badge/assegnati', [TeacherBadgesController::class, 'assigned']);
$router->post('/docenti/badge/save', [TeacherBadgesController::class, 'save']);
$router->post('/docenti/badge/{id}/delete', [TeacherBadgesController::class, 'delete']);
$router->get('/docenti/badge/api/materie/{subjectId}/argomenti', [TeacherBadgesController::class, 'topicsBySubject']);
$router->get('/docenti/badge/export/{subjectId}', [TeacherBadgesController::class, 'exportArchive']);
$router->post('/docenti/badge/import-file', [TeacherBadgesController::class, 'importArchive']);


// gestione poteri docenti
$router->get('/docenti/poteri', [TeacherPowersController::class, 'index']);
$router->get('/docenti/poteri/assegnati', [TeacherPowersController::class, 'assigned']);
$router->post('/docenti/poteri/save', [TeacherPowersController::class, 'save']);
$router->post('/docenti/poteri/{id}/delete', [TeacherPowersController::class, 'delete']);
$router->post('/docenti/poteri/import', [TeacherPowersController::class, 'import']);


// gestione materiali docenti
$router->get('/docenti/materiali', [TeacherMaterialsController::class, 'index']);
$router->post('/docenti/materiali/save', [TeacherMaterialsController::class, 'save']);
$router->post('/docenti/materiali/{id}/delete', [TeacherMaterialsController::class, 'delete']);

// gestione quest docenti
$router->get('/docenti/quest', [TeacherQuestController::class, 'index']);
$router->post('/docenti/quest/save', [TeacherQuestController::class, 'save']);
$router->post('/docenti/quest/{id}/delete', [TeacherQuestController::class, 'delete']);
$router->get('/docenti/quest/{id}/piantina', [TeacherQuestController::class, 'map']);
$router->get('/docenti/quest/{id}/consegne-non-valutate', [TeacherQuestController::class, 'unevaluatedDeliveries']);
$router->post('/docenti/quest/{questId}/consegne/{deliveryId}/problema', [TeacherQuestController::class, 'saveDeliveryProblem']);
$router->get('/docenti/quest/{id}/capitoli', [TeacherQuestController::class, 'chapterList']);
$router->post('/docenti/quest/{id}/capitoli', [TeacherQuestController::class, 'createChapter']);
$router->post('/docenti/quest/{questId}/capitoli/{chapterId}/update', [TeacherQuestController::class, 'updateChapter']);
$router->get('/docenti/quest/{questId}/capitoli/{chapterId}', [TeacherQuestController::class, 'chapterDetail']);
$router->get('/docenti/quest/{questId}/capitolo/{chapterId}', [TeacherQuestController::class, 'addExercise']);
$router->post('/docenti/quest/{questId}/capitolo/{chapterId}/save', [TeacherQuestController::class, 'saveExercise']);
$router->get('/docenti/quest/{questId}/capitolo/{chapterId}/esercizi/{exerciseId}/modifica', [TeacherQuestController::class, 'editExercise']);
$router->get('/docenti/quest/{questId}/capitolo/{chapterId}/esercizi/{exerciseId}/visualizza', [TeacherQuestController::class, 'viewExercise']);
$router->get('/docenti/quest/{questId}/capitolo/{chapterId}/esercizi/{exerciseId}/consegne', [TeacherQuestController::class, 'exerciseDeliveries']);
$router->get('/docenti/quest/{questId}/capitolo/{chapterId}/esercizi/{exerciseId}/consegne/{studentId}', [TeacherQuestController::class, 'studentDelivery']);
$router->post('/docenti/quest/{questId}/capitolo/{chapterId}/esercizi/{exerciseId}/update', [TeacherQuestController::class, 'updateExercise']);
$router->post('/docenti/quest/{questId}/capitolo/{chapterId}/esercizi/{exerciseId}/activate', [TeacherQuestController::class, 'activateExercise']);
$router->post('/docenti/quest/{questId}/capitolo/{chapterId}/esercizi/{exerciseId}/delete', [TeacherQuestController::class, 'deleteExercise']);
$router->post('/docenti/quest/{questId}/capitolo/{chapterId}/esercizi/{exerciseId}/consegne/{studentId}/save-valutazione', [TeacherQuestController::class, 'saveDeliveryEvaluation']);
$router->post('/docenti/quest/{questId}/capitolo/{chapterId}/esercizi/{exerciseId}/consegne/{studentId}/suggerisci-gemini', [TeacherQuestController::class, 'suggestGeminiEvaluation']);
$router->get('/docenti/quest/api/materie/{subjectId}/argomenti', [TeacherQuestController::class, 'exerciseTopics']);
$router->get('/docenti/quest/api/argomenti/{topicId}/materiali', [TeacherQuestController::class, 'exerciseMaterials']);
$router->post('/docenti/quest/api/editor/upload-image', [TeacherQuestController::class, 'uploadEditorImage']);
$router->get('/docenti/quest/import-export', [TeacherQuestController::class, 'importExportMenu']);
$router->get('/docenti/quest/import-export/altre-classi', [TeacherQuestController::class, 'externalOriginalQuests']);
$router->get('/docenti/quest/import-export/altre-classi/{questId}/esercizi', [TeacherQuestController::class, 'externalOriginalQuestExercises']);
$router->post('/docenti/quest/import-export/altre-classi/{questId}/importa', [TeacherQuestController::class, 'importOriginalQuestFromAnotherClass']);
$router->get('/docenti/quest/import-export/esporta/{questId}', [TeacherQuestController::class, 'exportQuestArchive']);
$router->post('/docenti/quest/import-export/importa-file', [TeacherQuestController::class, 'importQuestArchive']);


// gestione punizioni docenti
$router->get('/docenti/punizioni', [TeacherPunishmentsController::class, 'index']);
$router->get('/docenti/punizioni/assegnate', [TeacherPunishmentsController::class, 'assigned']);
$router->post('/docenti/punizioni/save', [TeacherPunishmentsController::class, 'save']);
$router->post('/docenti/punizioni/{id}/delete', [TeacherPunishmentsController::class, 'delete']);
$router->post('/docenti/punizioni/import', [TeacherPunishmentsController::class, 'import']);

// gestione personaggi docenti
$router->get('/docenti/personaggi', [TeacherCharacterController::class, 'index']);
$router->post('/docenti/personaggi/save', [TeacherCharacterController::class, 'save']);
$router->get('/docenti/personaggi/import-export', [TeacherCharacterController::class, 'importExportMenu']);
$router->get('/docenti/personaggi/import-export/altre-classi', [TeacherCharacterController::class, 'externalOriginalCharacters']);
$router->post('/docenti/personaggi/import-export/altre-classi/{characterId}/importa', [TeacherCharacterController::class, 'importOriginalCharacterFromAnotherClass']);
$router->get('/docenti/personaggi/import-export/esporta/{characterId}', [TeacherCharacterController::class, 'exportCharacterArchive']);
$router->post('/docenti/personaggi/import-export/importa-file', [TeacherCharacterController::class, 'importCharacterArchive']);


// gestione personalizzazioni docenti
$router->get('/docenti/personalizzazioni', [TeacherCustomizationController::class, 'index']);
$router->post('/docenti/personalizzazioni/save', [TeacherCustomizationController::class, 'save']);
$router->post('/docenti/personalizzazioni/costume', [TeacherCustomizationController::class, 'uploadCostume']);
$router->get('/docenti/personalizzazioni/giornate-sconti', [TeacherCustomizationController::class, 'discountDays']);
$router->post('/docenti/personalizzazioni/giornate-sconti/save', [TeacherCustomizationController::class, 'saveDiscountDay']);
$router->post('/docenti/personalizzazioni/giornate-sconti/{id}/delete', [TeacherCustomizationController::class, 'deleteDiscountDay']);
$router->get('/docenti/personalizzazioni/studenti', [TeacherCustomizationController::class, 'studentUploads']);
$router->post('/docenti/personalizzazioni/studenti/{id}/approve', [TeacherCustomizationController::class, 'approveStudentUpload']);
$router->post('/docenti/personalizzazioni/studenti/{id}/reject', [TeacherCustomizationController::class, 'rejectStudentUpload']);
$router->get('/docenti/personalizzazioni/in-uso', [TeacherCustomizationController::class, 'inUse']);
$router->get('/docenti/personalizzazioni/set', [TeacherCustomizationController::class, 'sets']);
$router->post('/docenti/personalizzazioni/set/save', [TeacherCustomizationController::class, 'saveSet']);
$router->post('/docenti/personalizzazioni/set/assegna', [TeacherCustomizationController::class, 'assignSetPersonalizations']);
$router->get('/docenti/personalizzazioni/set/esporta/{setId}', [TeacherCustomizationController::class, 'exportSetArchive']);
$router->post('/docenti/personalizzazioni/set/importa-file', [TeacherCustomizationController::class, 'importSetArchive']);

// alert e messaggi docenti
$router->get('/docenti/alerts', [TeacherCommunicationsController::class, 'alertsIndex']);
$router->get('/docenti/alerts/{id}/open', [TeacherCommunicationsController::class, 'openAlert']);
$router->post('/docenti/alerts/read-all', [TeacherCommunicationsController::class, 'markAllAlertsAsRead']);
$router->post('/docenti/alerts/{id}/delete', [TeacherCommunicationsController::class, 'deleteAlert']);
$router->get('/docenti/messages', [TeacherCommunicationsController::class, 'messagesIndex']);
$router->get('/docenti/messages/new-bulk', [TeacherCommunicationsController::class, 'composeBulkMessage']);
$router->post('/docenti/messages/new-bulk', [TeacherCommunicationsController::class, 'storeBulkMessage']);
$router->get('/docenti/messages/{id}', [TeacherCommunicationsController::class, 'showMessage']);
$router->post('/docenti/messages/{id}/reply', [TeacherCommunicationsController::class, 'replyToMessage']);
$router->post('/docenti/messages/delete', [TeacherCommunicationsController::class, 'deleteMessages']);

// selezione classi docenti
$router->get('/docenti/classi', [TeacherClassController::class, 'index']);
$router->post('/docenti/classi', [TeacherClassController::class, 'store']);
$router->post('/docenti/classi/{id}/modifica', [TeacherClassController::class, 'update']);
$router->get('/docenti/classi/{id}/seleziona', [TeacherClassController::class, 'select']);
$router->get('/docenti/profilo', [TeacherProfileController::class, 'index']);
$router->post('/docenti/profilo', [TeacherProfileController::class, 'update']);
$router->get('/docenti/plugin', [TeacherPluginsController::class, 'index']);
$router->post('/docenti/plugin/classe', [TeacherPluginsController::class, 'saveClassPlugins']);
$router->post('/docenti/plugin/nuovo', [TeacherPluginsController::class, 'create']);
(new PluginRouteRegistrar())->register($router);
$router->get('/docenti/plugin/{pluginCode}', [PluginPageController::class, 'teacher']);

// dashboard studenti
$router->get('/studenti/dashboard', [StudentDashboardController::class, 'index']);
$router->get('/studenti/profilo', [StudentProfileController::class, 'index']);
$router->post('/studenti/profilo', [StudentProfileController::class, 'update']);
$router->get('/studenti/plugin/{pluginCode}', [PluginPageController::class, 'student']);
$router->get('/studenti/classi/{id}/seleziona', [StudentDashboardController::class, 'selectClass']);
$router->get('/studenti/classe/dashboard', [StudentDashboardController::class, 'showClassDashboard']);
$router->post('/studenti/classe/potere-squadra', [StudentDashboardController::class, 'activateTeamPower']);
$router->get('/studenti/compagni', [StudentDashboardController::class, 'showClassmates']);
$router->post('/studenti/classe/personaggio', [StudentDashboardController::class, 'chooseCharacter']);
$router->get('/studenti/poteri', [StudentPowersController::class, 'index']);
$router->post('/studenti/poteri/use', [StudentPowersController::class, 'usePower']);
$router->get('/studenti/poteri/aggiungi', [StudentPowersController::class, 'add']);
$router->post('/studenti/poteri/aggiungi', [StudentPowersController::class, 'choosePower']);
$router->get('/studenti/personalizzazioni', [StudentCustomizationController::class, 'index']);
$router->post('/studenti/personalizzazioni/save', [StudentCustomizationController::class, 'save']);
$router->post('/studenti/personalizzazioni/sell', [StudentCustomizationController::class, 'sell']);
$router->get('/studenti/personalizzazioni/negozio', [StudentCustomizationController::class, 'shop']);
$router->post('/studenti/personalizzazioni/negozio/buy', [StudentCustomizationController::class, 'buy']);
$router->post('/studenti/personalizzazioni/negozio/upload', [StudentCustomizationController::class, 'uploadStudentCustomization']);
$router->get('/studenti/personalizzazioni/equipaggiamento', [StudentCustomizationController::class, 'equipment']);
$router->post('/studenti/personalizzazioni/equipaggiamento/buy', [StudentCustomizationController::class, 'buyEquipment']);
$router->post('/studenti/personalizzazioni/equipaggiamento/equip', [StudentCustomizationController::class, 'equip']);
$router->post('/studenti/personalizzazioni/equipaggiamento/sell', [StudentCustomizationController::class, 'sellEquipment']);
$router->get('/studenti/forzieri', [StudentChestsController::class, 'index']);
$router->post('/studenti/forzieri/apri', [StudentChestsController::class, 'openChest']);
$router->get('/studenti/badge', [StudentBadgesController::class, 'index']);
$router->get('/studenti/punizioni', [StudentPunishmentsController::class, 'index']);
$router->post('/studenti/punizioni/consegna', [StudentPunishmentsController::class, 'upload']);
$router->get('/studenti/quest', [StudentQuestController::class, 'index']);
$router->get('/studenti/quest/{questId}/piantina', [StudentQuestController::class, 'map']);
$router->get('/studenti/quest/{questId}/problemi', [StudentQuestController::class, 'problemDeliveries']);
$router->get('/studenti/quest/{questId}/capitoli/{chapterId}', [StudentQuestController::class, 'chapterDetail']);
$router->get('/studenti/quest/{questId}/capitoli/{chapterId}/esercizi/{exerciseId}', [StudentQuestController::class, 'exerciseDetail']);
$router->post('/studenti/quest/{questId}/capitoli/{chapterId}/esercizi/{exerciseId}/consegna', [StudentQuestController::class, 'submitExercise']);
$router->post('/studenti/quest/{questId}/capitoli/{chapterId}/esercizi/{exerciseId}/consegna/elimina-file', [StudentQuestController::class, 'deleteDeliveredFile']);

$router->get('/studenti/squadra', [StudentTeamsController::class, 'index']);
$router->post('/studenti/squadra/save', [StudentTeamsController::class, 'save']);
$router->post('/studenti/squadra/{id}/delete', [StudentTeamsController::class, 'delete']);
$router->post('/studenti/squadra/lascia', [StudentTeamsController::class, 'leave']);
$router->post('/studenti/squadra/invito', [StudentTeamsController::class, 'handleInvite']);

// alert e messaggi studenti
$router->get('/studenti/alerts', [StudentCommunicationsController::class, 'alertsIndex']);
$router->get('/studenti/alerts/{id}/open', [StudentCommunicationsController::class, 'openAlert']);
$router->post('/studenti/alerts/read-all', [StudentCommunicationsController::class, 'markAllAlertsAsRead']);
$router->post('/studenti/alerts/{id}/delete', [StudentCommunicationsController::class, 'deleteAlert']);
$router->get('/studenti/messages', [StudentCommunicationsController::class, 'messagesIndex']);
$router->get('/studenti/messages/new', [StudentCommunicationsController::class, 'composeMessage']);
$router->post('/studenti/messages', [StudentCommunicationsController::class, 'storeMessage']);
$router->get('/studenti/messages/{id}', [StudentCommunicationsController::class, 'showMessage']);
$router->post('/studenti/messages/{id}/reply', [StudentCommunicationsController::class, 'replyToMessage']);
$router->post('/studenti/messages/delete', [StudentCommunicationsController::class, 'deleteMessages']);

//Test Creator
$router->get('/testcreator/begin', [TestCreatorBeginController::class, 'beginTestCreator']);
$router->get('/testcreator/materie', [TestCreatorBeginController::class, 'subjects']);
$router->get('/testcreator/materie/{subjectId}/form-data', [TestCreatorBeginController::class, 'subjectFormData']);
$router->post('/testcreator/materie/save', [TestCreatorBeginController::class, 'saveSubject']);
$router->get('/testcreator/materie/{subjectId}/export-json', [TestCreatorBeginController::class, 'exportSubjectJson']);
$router->post('/testcreator/materie/import-json', [TestCreatorBeginController::class, 'importSubjectJson']);
$router->post('/testcreator/materie/{subjectId}/assegna', [TestCreatorBeginController::class, 'assignSubject']);
$router->post('/testcreator/materie/{subjectId}/delete', [TestCreatorBeginController::class, 'deleteSubject']);
$router->post('/testcreator/materie/{subjectId}/disassegna', [TestCreatorBeginController::class, 'unassignSubject']);
$router->get('/testcreator/argomenti', [TestCreatorBeginController::class, 'topics']);
$router->post('/testcreator/argomenti/save', [TestCreatorBeginController::class, 'saveTopic']);
$router->post('/testcreator/argomenti/{topicId}/delete', [TestCreatorBeginController::class, 'deleteTopic']);
$router->get('/testcreator/domande', [TestCreatorBeginController::class, 'questionTopics']);
$router->get('/testcreator/domande/argomenti/{topicId}', [TestCreatorBeginController::class, 'questionsByTopic']);
$router->get('/testcreator/domande/argomenti/{topicId}/nuova', [TestCreatorBeginController::class, 'newQuestion']);
$router->post('/testcreator/domande/save', [TestCreatorBeginController::class, 'saveQuestion']);
$router->get('/testcreator/domande/{questionId}/modifica', [TestCreatorBeginController::class, 'editQuestion']);
$router->post('/testcreator/domande/{questionId}/update', [TestCreatorBeginController::class, 'updateQuestion']);
$router->post('/testcreator/domande/{questionId}/remove', [TestCreatorBeginController::class, 'removeQuestion']);
$router->post('/testcreator/domande/{questionId}/remove-permanent', [TestCreatorBeginController::class, 'removeQuestionPermanently']);
$router->post('/testcreator/domande/api/editor/upload-image', [TestCreatorBeginController::class, 'uploadQuestionEditorImage']);
$router->get('/testcreator/import-domande', [TestCreatorBeginController::class, 'importQuestionsMenu']);
$router->get('/testcreator/esporta-domande', [TestCreatorBeginController::class, 'exportQuestions']);
$router->post('/testcreator/esporta-domande/csv', [TestCreatorBeginController::class, 'exportQuestionsCsv']);
$router->get('/testcreator/import-domande/argomenti/{topicId}/altri-docenti', [TestCreatorBeginController::class, 'importQuestionsFromOtherTeachers']);
$router->post('/testcreator/import-domande/{questionId}/importa', [TestCreatorBeginController::class, 'importQuestionFromOtherTeacher']);
$router->get('/testcreator/import-domande/{questionId}/risposte', [TestCreatorBeginController::class, 'importQuestionAnswersPreview']);
$router->get('/testcreator/import-domande/csv', [TestCreatorBeginController::class, 'importQuestionsFromCsvForm']);
$router->post('/testcreator/import-domande/csv', [TestCreatorBeginController::class, 'importQuestionsFromCsvSubmit']);
$router->get('/testcreator/import-domande/argomenti/{topicId}/csv', [TestCreatorBeginController::class, 'importQuestionsFromCsvForm']);
$router->post('/testcreator/import-domande/argomenti/{topicId}/csv', [TestCreatorBeginController::class, 'importQuestionsFromCsvSubmit']);
$router->get('/testcreator/libri', [TestCreatorBeginController::class, 'books']);
$router->get('/testcreator/libri/{bookId}/form-data', [TestCreatorBeginController::class, 'bookFormData']);
$router->post('/testcreator/libri/save', [TestCreatorBeginController::class, 'saveBook']);
$router->post('/testcreator/libri/{bookId}/disattiva', [TestCreatorBeginController::class, 'deactivateBook']);
$router->get('/testcreator/griglie', [TestCreatorBeginController::class, 'grids']);
$router->get('/testcreator/griglie/nuova', [TestCreatorBeginController::class, 'newGrid']);
$router->get('/testcreator/griglie/{gridId}/modifica', [TestCreatorBeginController::class, 'editGrid']);
$router->post('/testcreator/griglie/save', [TestCreatorBeginController::class, 'saveGrid']);
$router->post('/testcreator/griglie/{gridId}/update', [TestCreatorBeginController::class, 'updateGrid']);
$router->post('/testcreator/griglie/{gridId}/delete', [TestCreatorBeginController::class, 'deleteGrid']);
$router->get('/testcreator/quiz', [TestCreatorBeginController::class, 'quizzes']);
$router->get('/testcreator/quiz/nuovo', [TestCreatorBeginController::class, 'newQuiz']);
$router->post('/testcreator/quiz/save', [TestCreatorBeginController::class, 'saveQuiz']);
$router->get('/testcreator/quiz/{quizId}/modifica', [TestCreatorBeginController::class, 'editQuiz']);
$router->post('/testcreator/quiz/{quizId}/update', [TestCreatorBeginController::class, 'updateQuiz']);
$router->post('/testcreator/quiz/{quizId}/delete', [TestCreatorBeginController::class, 'deleteQuiz']);
$router->get('/testcreator/quiz/{quizId}/genera', [TestCreatorBeginController::class, 'generateQuiz']);
$router->get('/testcreator/quiz/{quizId}/stampa', [TestCreatorBeginController::class, 'printQuiz']);
$router->get('/testcreator/quiz/{quizId}/stampa-dsa', [TestCreatorBeginController::class, 'printDsaOptions']);
$router->get('/testcreator/quiz/{quizId}/esporta-csv', [TestCreatorBeginController::class, 'exportQuizCsv']);
$router->get('/testcreator/quiz/{quizId}/co-correzione', [TestCreatorBeginController::class, 'printCoCorrection']);
$router->get('/testcreator/quiz/{quizId}/domande-selezione', [TestCreatorBeginController::class, 'manualQuestionSelection']);
$router->post('/testcreator/quiz/{quizId}/domande-selezione', [TestCreatorBeginController::class, 'saveManualQuestionSelection']);
$router->get('/testcreator/template-stampa', [TestCreatorBeginController::class, 'printTemplates']);
$router->get('/testcreator/mail-docenti', [TestCreatorBeginController::class, 'teacherEmails']);
$router->post('/testcreator/mail-docenti/save', [TestCreatorBeginController::class, 'saveTeacherEmail']);
$router->post('/testcreator/mail-docenti/import-csv', [TestCreatorBeginController::class, 'importTeacherEmailsCsv']);
$router->post('/testcreator/mail-docenti/{emailId}/delete', [TestCreatorBeginController::class, 'deleteTeacherEmail']);
$router->get('/testcreator/amministratori', [TestCreatorBeginController::class, 'administrators']);
$router->post('/testcreator/amministratori/{userId}/promuovi', [TestCreatorBeginController::class, 'promoteAdministrator']);
$router->post('/testcreator/amministratori/{userId}/rimuovi', [TestCreatorBeginController::class, 'removeAdministrator']);
$router->post('/testcreator/template-stampa/seleziona', [TestCreatorBeginController::class, 'selectPrintTemplate']);
$router->post('/testcreator/template-stampa/upload', [TestCreatorBeginController::class, 'uploadPrintTemplate']);
