addEventListener('turbo:load', () => {
    initializeDragAndDrop();
})

addEventListener('turbo:frame-render', () => {
    initializeDragAndDrop();
})

function handleDragStart(event: DragEvent) {
    let target = event.target as HTMLElement;
    target = target.closest('.simsy_cms_editor_block') as HTMLElement;
    if (target) {
        // Store the block id in dataTransfer to access it during drop
        event.dataTransfer?.setData('blockId', target.dataset.blockId || '');
        // Store the originating section id to reference later
        event.dataTransfer?.setData('sectionId', target.closest('.simsy_cms_section_content')?.id || '');
        // Add a class to highlight the element being dragged
        target.classList.add('dragging');
    }
}

function handleDragOver(event: DragEvent) {
    event.preventDefault();  // Necessary to allow drop
    const target = event.target as HTMLElement;
    if (target?.classList.contains('block-selector-add')) {
        target.classList.add('drag-over');
    }
}

function handleDragLeave(event: DragEvent) {
    let target = event.target as HTMLElement;
    target = target.closest('.block-selector-add') as HTMLElement;
    if (target) {
        target.classList.remove('drag-over');
    }
}

function handleDrop(event: DragEvent) {
    event.preventDefault();
    let target = event.target as HTMLElement;
    target = target.closest('.simsy_cms_editor_block') as HTMLElement;
    const draggedBlockId = event.dataTransfer?.getData('blockId') || '';

    // Locate the dragged block and the drop target
    const draggedBlock = document.querySelector(`[data-block-id="${draggedBlockId}"]`) as HTMLElement;
    const targetSection = target.closest('.simsy_cms_section_content');

    if (target && draggedBlock && targetSection) {
        target.classList.remove('drag-over');

        // Reposition the dragged block within the new section
        targetSection.insertBefore(draggedBlock, target);

        let blocks = [];

        // Get the new order of the blocks
        targetSection.querySelectorAll('.simsy_cms_editor_block').forEach((block, index) => {
            if (block.getAttribute('data-block-id')) {
                blocks[index] = {
                    id: block.getAttribute('data-block-id'),
                    sectionId: targetSection.getAttribute('data-section-id'),
                };
            }
        });

        fetch(targetSection.getAttribute('data-order-url'), {
            method: 'POST',
            body: JSON.stringify({
                blocks: blocks
            })
        })
    }
}

function handleDragEnd(event: DragEvent) {
    let target = event.target as HTMLElement;
    target = target.closest('.simsy_cms_editor_block') as HTMLElement;
    if (target) {
        target.classList.remove('dragging');
    }

    // Remove any drag-over classes
    const allBlocks = document.querySelectorAll('.block-selector-add');
    allBlocks.forEach(block => block.classList.remove('drag-over'));
}

function initializeDragAndDrop() {
    const sections = document.querySelectorAll('.simsy_cms_section_content');

    sections.forEach(section => {
        const blocks = section.querySelectorAll('.simsy_cms_editor_block');

        blocks.forEach(block => {
            block.addEventListener('dragstart', handleDragStart);
            block.addEventListener('dragover', handleDragOver);
            block.addEventListener('dragleave', handleDragLeave);
            block.addEventListener('drop', handleDrop);
            block.addEventListener('dragend', handleDragEnd);
        });
    });
}