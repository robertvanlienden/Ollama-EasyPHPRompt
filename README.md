# Olllama test
Simple test project for Ollama.

## Requirements
- Docker

## How to use
1. `./Taskfile init`
2. `./Taskfile shell` => `php bin/console messenger:consume async` in the container to consume messages
3. http://localhost/ => Add some ollama_request
4. http://localhost/ollama/request/ to view your request. Output is available when processed by the queue.