window.addEventListener('turbo:load', () => {
    const addBtn = document.getElementById('page-section-add') as HTMLDivElement;

    if (addBtn) {
        const rightPanel = document.getElementById('right-panel-frame') as HTMLDivElement;
        const sectionFrame = document.getElementById('page-section-frame') as HTMLDivElement;
        const pageId = sectionFrame.getAttribute('data-page-id');

        addBtn.addEventListener('click', () => {
            rightPanel.setAttribute('src', addBtn.getAttribute('data-url'));
        });
    }

    const editConfigBtn = document.getElementById('page-configuration-edit') as HTMLDivElement;

    if (editConfigBtn) {
        const rightPanel = document.getElementById('right-panel-frame') as HTMLDivElement;

        editConfigBtn.addEventListener('click', () => {
            rightPanel.setAttribute('src', editConfigBtn.getAttribute('data-url'));
        });
    }

    addEventListener('turbo:frame-render', (e) => {
        const frame = e.target as HTMLDivElement;
        const blockSelectorContainers = frame.querySelectorAll('.block-selector');

        blockSelectorContainers.forEach((container) => {
            const addBtnContainer: HTMLDivElement = container.querySelector('.block-selector-add');
            const addBtn = addBtnContainer.querySelector('.block-selector-add-toggle') as HTMLDivElement;
            const closeToggle = container.querySelector('.block-selector-add-close') as HTMLDivElement;
            const blockSelectorBlocks: HTMLDivElement = container.querySelector('.block-selector-blocks');

            closeToggle.addEventListener('click', () => {
                addBtnContainer.classList.remove('selecting');
                blockSelectorBlocks.classList.add('d-none');
            });

            addBtn.addEventListener('click', () => {
                addBtnContainer.classList.add('selecting');
                let selectorLeft = Number(getComputedStyle(addBtn).left.replace('px', ''));

                if (selectorLeft < blockSelectorBlocks.getBoundingClientRect().width / 2) {
                    selectorLeft = blockSelectorBlocks.getBoundingClientRect().width / 2;
                } else if (selectorLeft > addBtnContainer.getBoundingClientRect().width - blockSelectorBlocks.getBoundingClientRect().width / 2) {
                    selectorLeft = addBtnContainer.getBoundingClientRect().width - blockSelectorBlocks.getBoundingClientRect().width / 2;
                }

                blockSelectorBlocks.style.left = `${selectorLeft}px`;
            });

            addBtnContainer.addEventListener('mousemove', (e: MouseEvent) => {
                if (!addBtnContainer.classList.contains('selecting')) {
                    let left = e.clientX - addBtnContainer.getBoundingClientRect().left;

                    if (left < addBtn.getBoundingClientRect().width / 2) {
                        left = addBtn.getBoundingClientRect().width / 2;
                    } else if (left > addBtnContainer.getBoundingClientRect().width - addBtn.getBoundingClientRect().width / 2) {
                        left = addBtnContainer.getBoundingClientRect().width - addBtn.getBoundingClientRect().width / 2;
                    }

                    addBtn.style.left = `${left}px`;
                }
            });
        });

        const blockViews = document.querySelectorAll('.simsy_cms_block_view');
        blockViews.forEach(view => {
            const editBtn = view.parentNode.querySelector('.simsy_cms_block_edit_btn') as HTMLDivElement;

            view.addEventListener('dblclick', () => {
                editBtn.click();
            })
        })

        const sectionCollapseToggles: NodeListOf<HTMLDivElement> = frame.querySelectorAll('.section-collapse-toggle');
        sectionCollapseToggles.forEach(toggle => {
            const section = toggle.closest('.simsy_cms_section') as HTMLDivElement;
            const sectionContent = section.querySelector('.simsy_cms_section_content') as HTMLDivElement;
            const sectionCollapseIcon = toggle.querySelector('.bi') as HTMLDivElement;
            sectionCollapseIcon.classList.toggle('bi-arrows-collapse', !sectionContent.classList.contains('d-none'));
            sectionCollapseIcon.classList.toggle('bi-arrows-expand', sectionContent.classList.contains('d-none'));

            toggle.removeEventListener('click', () => {
                handleSectionCollapse(toggle);
            });
            toggle.addEventListener('click', () => {
                handleSectionCollapse(toggle);
            });
        });
    });
});

function handleSectionCollapse(toggle: HTMLDivElement) {
    const section = toggle.closest('.simsy_cms_section') as HTMLDivElement;
    const sectionContent = section.querySelector('.simsy_cms_section_content') as HTMLDivElement;
    const sectionCollapseIcon = toggle.querySelector('.bi') as HTMLDivElement;

    console.log(section, sectionContent, sectionCollapseIcon);

    sectionContent.classList.toggle('d-none');
    sectionCollapseIcon.classList.toggle('bi-arrows-collapse', !sectionContent.classList.contains('d-none'));
    sectionCollapseIcon.classList.toggle('bi-arrows-expand', sectionContent.classList.contains('d-none'));
}