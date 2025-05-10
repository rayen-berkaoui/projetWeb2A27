<?php
$input = "Génère un mot de passe sécurisé, unique, complexe mais mémorisable pour un utilisateur nommé Delizar. Le mot de passe doit faire 12 caractères.";

$data = [
    "model" => "gpt-3.5-turbo",
    "messages" => [
        ["role" => "user", "content" => $input]
    ],
    "max_tokens" => 50,
    "temperature" => 0.8
];

$options = [
    'http' => [
        'method'  => 'POST',
        'header'  => "Content-Type: application/json\r\nAuthorization: Bearer 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\r\n",
        'content' => json_encode($data),
        'ignore_errors' => true
    ]
];

$context  = stream_context_create($options);
$result = file_get_contents("https://api.openai.com/v1/chat/completions", false, $context);

if ($result === FALSE) {
    echo json_encode(["error" => "Erreur lors de l'appel à l'API OpenAI."]);
    exit;
}

$response = json_decode($result, true);

if (isset($response['choices'][0]['message']['content'])) {
    // Nettoyage du mot de passe (suppression de retours à la ligne, guillemets éventuels)
    $password = trim($response['choices'][0]['message']['content']);
    $password = strip_tags($password); // au cas où
    $password = preg_replace('/[^a-zA-Z0-9@#%!\-_$]/', '', $password); // garder les caractères forts
    echo $password;
} else {
    echo json_encode(["error" => "Aucune recommandation de mot de passe reçue."]);
}
?>
