import * as bootstrap from 'bootstrap';

window.addEventListener('DOMContentLoaded', () => {
    const addBtn = document.getElementById('page-section-add') as HTMLDivElement;
    const sectionAccordion = document.getElementById('page-section-accordion') as HTMLDivElement;

    if (sectionAccordion) {
        const blockSelectionHTML = sectionAccordion.getAttribute('data-block-selection-html');
        addBtn.addEventListener('click', () => {
            sectionAccordion.appendChild(createNewSection());
            new bootstrap.Collapse(sectionAccordion.lastElementChild.querySelector('.accordion-collapse'));
        });
    }

    function createNewSection(): HTMLDivElement {
        let section = document.createElement('div');
        const index = 'new' + sectionAccordion.children.length;

        section.classList.add('accordion-item');
        section.setAttribute('data-index', index.toString());
        section.innerHTML = `
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#sectionCollapseItem${index}" aria-expanded="false"
                        aria-controls="sectionCollapseItem${index}">
                    New section
                </button>
            </h2>
            <div id="sectionCollapseItem${index}" class="accordion-collapse collapse"
                 data-bs-parent="#page-section-accordion">
                <div class="accordion-body">
                    <label for="section-name-${index}">Name</label>
                    <input type="text" id="section-name-${index}" name="section-name-${index}"
                           class="simsy-input">

                    <label for="section-description-${index}">Description</label>
                    <textarea id="section-description-${index}"
                              name="section-description-${index}"
                              class="simsy-input"></textarea>
                </div>
            </div>
        `;

        const nameInput = section.querySelector(`#section-name-${index}`) as HTMLInputElement;

        function saveSectionCallback() {
            nameInput.removeEventListener('change', saveSectionCallback);
            return saveSection(section);
        }

        nameInput.addEventListener('change', saveSectionCallback);

        return section;
    }

    function saveSection(section: HTMLDivElement) {
        const name: HTMLInputElement = section.querySelector('#section-name-' + section.getAttribute('data-index'));
        const description: HTMLTextAreaElement = section.querySelector('#section-description-' + section.getAttribute('data-index'));
        const page = sectionAccordion.getAttribute('data-page-id');

        fetch('/api/section/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                page: page,
                name: name.value,
                description: description.value
            })
        }).then(response => response.json())
            .then(json => {
                console.log(json);
                section.setAttribute('data-section-id', json.sectionId);
            })
    }
});