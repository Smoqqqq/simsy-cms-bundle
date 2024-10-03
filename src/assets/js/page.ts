window.addEventListener('turbo:load', () => {
    const addBtn = document.getElementById('page-section-add') as HTMLDivElement;

    if (addBtn) {
        const rightPanel = document.getElementById('right-panel-frame') as HTMLDivElement;
        const sectionFrame = document.getElementById('page-section-frame') as HTMLDivElement;
        const pageId = sectionFrame.getAttribute('data-page-id');

        addBtn.addEventListener('click', () => {
            rightPanel.setAttribute('src', `/page/${pageId}/section/add`);
        });
    }

    const editConfigBtn = document.getElementById('page-configuration-edit') as HTMLDivElement;

    if (editConfigBtn) {
        const rightPanel = document.getElementById('right-panel-frame') as HTMLDivElement;
        const sectionFrame = document.getElementById('page-section-frame') as HTMLDivElement;
        const pageId = sectionFrame.getAttribute('data-page-id');

        editConfigBtn.addEventListener('click', () => {
            rightPanel.setAttribute('src', `/page/${pageId}/edit/infos`);
        });
    }
});