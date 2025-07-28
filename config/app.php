<?php

return [
    'name' => 'cat-AI',
    'version' => '1.0.0',
    'description' => 'ur purrfect AI companion',
    
    // API Configuration - Add your API keys here
    'cat_api' => [
        'base_url' => 'https://api.thecatapi.com/v1',
        'api_key' => '', // TODO: Add your Cat API key
        'images_endpoint' => '/images/search',
        'facts_endpoint' => 'https://catfact.ninja/fact'
    ],
    
    'groq_api' => [
        'base_url' => 'https://api.groq.com/openai/v1',
        'api_key' => '', // TODO: Add your Groq API key
        'model' => 'llama-3.1-70b-versatile',
        'max_tokens' => 1000,
        'temperature' => 0.7
    ],
    
    // System prompt for Groq API
    'system_prompt' => 'You are a helpful cat-themed AI assistant. Always respond in a friendly, cat-like manner using lowercase and "ur" instead of "your". Format your responses as "meow (actual response)".',
    
    'themes' => [
        'dark' => [
            'name' => 'dark mode',
            'icon' => '🌙'
        ],
        'light' => [
            'name' => 'light mode',
            'icon' => '☀️'
        ]
    ],
    
    // Database configuration (if needed)
    'database' => [
        'enabled' => false, // Set to true when connecting to database
        'host' => '', // TODO: Add database host
        'name' => '', // TODO: Add database name
        'username' => '', // TODO: Add database username
        'password' => '' // TODO: Add database password
    ]
];

?>