# Olllama test
## Warning; Just some quick-and-dirty POC! Don't expect nice and shiny things!

Simple test project for Ollama (local use LLM).

With this project, you can simply add `PromtTypes` to re-use on every request.

## Requirements
- Docker

## How to use
1. `./Taskfile init` (you may need to do this 2 times, too lazy to fix migrations for both worker and PHP container...)
2. http://localhost:3000/ and download the `llama3` model
3. Add some prompts to http://localhost/prompt/type/
   a. Example; **Name:** `Blog`. **Prompt Prefix:** `Write a blog article with the following keywords:`
4. http://localhost/ => Add some ollama_request with the prompt type you just created
5. http://localhost/ollama/request/ to view your request. Output is available when processed by the queue.