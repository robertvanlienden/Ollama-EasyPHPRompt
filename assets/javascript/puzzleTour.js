document.addEventListener('DOMContentLoaded', function() {
    const questionsWrapper = document.getElementById('questions-wrapper');
    const addQuestionButton = document.getElementById('add-question');

    // Function to attach event listener to the "Add Answer" button inside a question template
    function attachAnswerEventListeners(questionTemplate) {
        const addAnswerButton = questionTemplate.querySelector('.add-answer');
        if (addAnswerButton) {
            addAnswerButton.addEventListener('click', function(e) {
                console.log(e.target.parentElement.querySelector('fieldset'));
                const answersWrapper = e.target.parentElement.querySelector('.answers-wrapper') ?? e.target.parentElement.querySelector('fieldset');
                const answersPrototype = answersWrapper.dataset.prototype ?? answersWrapper.querySelector('div').dataset.prototype;
                const answersIndex = answersWrapper.children.length;

                const newAnswerForm = answersPrototype.replace(/__name__/g, answersIndex);
                const answerTemplate = document.createElement('div');
                answerTemplate.classList.add('answer');
                answerTemplate.innerHTML = newAnswerForm;

                answersWrapper.appendChild(answerTemplate);
                const answers = answersWrapper.querySelectorAll('.answer')
                answers[answers.length - 1].innerHTML += '<button type="button" class="btn btn-danger remove-answer">Remove Answer</button>';
            });
        }
    }

    // Function to attach event listener to the "Add Question" button
    function attachQuestionEventListeners(questionTemplate) {
        const addAnswerButton = questionTemplate.querySelector('.add-answer');
        if (addAnswerButton) {
            addAnswerButton.addEventListener('click', function(e) {
                const answersWrapper = questionTemplate.querySelector('.answers-wrapper');
                const answersPrototype = answersWrapper.dataset.prototype;
                const answersIndex = answersWrapper.children.length;

                const newAnswerForm = answersPrototype.replace(/__name__/g, answersIndex);
                const answerTemplate = document.createElement('div');
                answerTemplate.classList.add('answer');
                answerTemplate.innerHTML = newAnswerForm;

                answersWrapper.appendChild(answerTemplate);
            });
        }
    }

    // Attach event listener to the "Add Question" button
    addQuestionButton.addEventListener('click', function() {
        const prototype = questionsWrapper.dataset.prototype;
        const index = questionsWrapper.children.length;
        const newForm = prototype.replace(/__name__/g, index);
        const questionTemplate = document.createElement('div');
        questionTemplate.classList.add('question');
        questionTemplate.innerHTML = newForm;

        // Add buttons inside the question template
        questionTemplate.innerHTML += `
            <button type="button" class="btn btn-success add-answer">Add Answer</button>
            <button type="button" class="btn btn-danger remove-question">Remove Question</button>
        `;

        questionsWrapper.appendChild(questionTemplate);

        // Attach event listener to the "Add Answer" button inside the new question template
        attachAnswerEventListeners(questionTemplate);
    });

    // Attach event listeners to existing question templates
    questionsWrapper.querySelectorAll('.question').forEach(function(questionTemplate) {
        attachAnswerEventListeners(questionTemplate);
    });

    // Event delegation for removing answers
    questionsWrapper.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-answer')) {
            event.target.parentElement.remove();
        }
    });

    // Event delegation for removing questions
    questionsWrapper.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-question')) {
            event.target.parentElement.remove();
        }
    });
});
