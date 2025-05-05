<?php
$input = "Génère un mot de passe sécurisé pour un utilisateur nommé Delizar, avec 12 caractères.";
$data = [
    "model" => "gpt-3.5-turbo",
    "messages" => [
        ["role" => "user", "content" => $input]
    ]
];
$options = [
    'http' => [
        'method'  => 'POST',
        'header'  => "Content-Type: application/json\r\nAuthorization: Bearer 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\r\n",  // Remplace par ta clé API
        'content' => json_encode($data),
        'ignore_errors' => true // Ajouter cette ligne pour ignorer les erreurs de connexion
    ]
];
$context  = stream_context_create($options);
$result = file_get_contents("https://api.openai.com/v1/chat/completions", false, $context);

// Vérifier si la réponse est correcte
if ($result === FALSE) {
    die('Erreur de requête API');
}

// Afficher la réponse brute pour débogage
echo "<pre>";
print_r($result);
echo "</pre>";

// Décoder la réponse JSON
$response = json_decode($result, true);

// Vérifier si le mot de passe est présent dans la réponse
if (isset($response['choices'][0]['message']['content'])) {
    $password = trim($response['choices'][0]['message']['content']);
    echo "Mot de passe généré : " . $password;
} else {
    echo "Aucune recommandation de mot de passe reçue. Voici la réponse brute :";
    echo "<pre>";
    print_r($response);  // Afficher la réponse JSON complète
    echo "</pre>";
}
?>
