# Olllama test
## Warning; Just some quick-and-dirty POC! Don't expect nice and shiny things!

Simple test project for Ollama (local use LLM).

With this project, you can simply add `PromtTypes` to re-use on every request.

## Features
- Pre-define prompt types for re-use
- Add 

## Example use
### Generating Blog Articles
- Add a `Prompt Type` with the following PromptPrefix: `Write a blog article with the following keywords:`
- Use this Prompt Type on http://localhost/ and fill in the `data` with keywords for your 

### Parse datasets
- Add a `Prompt Type` with the following PromptPrefix: `Parse the following data to the format: Name, Date of Birth`
- Use this Prompt Type on http://localhost/ and fill in the `data` with information you have.

## Requirements
- Docker

## How to use
1. `./Taskfile init` (you may need to do this 2 times, too lazy to fix migrations for both worker and PHP container...)
2. Download the `llama3` model by running `./Taskfile ollama-pull llama3` (or your own model, you can switch to your favorite model in the `.env`)
3. Add some prompts to http://localhost/prompt/type/
   a. Example; **Name:** `Blog`. **Prompt Prefix:** `Write a blog article with the following keywords:`
4. http://localhost/ => Add some ollama_request with the prompt type you just created
5. http://localhost/ollama/request/ to view your request. Output is available when processed by the queue.

Run `./Taskfile` for a complete list of `Taskfile` commands.