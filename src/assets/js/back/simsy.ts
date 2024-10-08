declare var Translator; // Define the Translator variable that is included in the layout.html.twig file

import '../../styles/back/simsy.scss';

import './page';
import './block-drag';
import '@hotwired/turbo';
import 'bootstrap';
import {TurboBeforeFrameRenderEvent, TurboFrameRenderEvent} from "@hotwired/turbo";
import * as bootstrap from 'bootstrap';

addEventListener('turbo:load', () => {
    handleFrameRendering();
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
});

function handleFrameRendering() {
    addEventListener('turbo:before-frame-render', (e: TurboBeforeFrameRenderEvent) => {
        const baseFrame = document.getElementById('right-panel-frame');

        if (e.detail.newFrame.id !== 'right-panel-frame') {
            return;
        }

        if (e.detail.newFrame.querySelector('#close-right-panel')) {
            baseFrame.setAttribute('src', '');
            setTimeout(() => {
                baseFrame.innerHTML = '<div id="right-panel-loader">Loading...</div>';
            }, 200);
        }
    })

    addEventListener("turbo:frame-render", (event: TurboFrameRenderEvent) => {
        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
        const popoverList = [...popoverTriggerList].map(popoverTriggerEl => {
            let config = {};

            if (popoverTriggerEl.getAttribute('data-sanitize') === 'false') {
                config = {
                    sanitize: false,
                    html: true,
                    container: document.querySelector(popoverTriggerEl.getAttribute('data-container')) || 'body'
                };
            }

            popoverTriggerEl.addEventListener('click', (e) => {
                const target = e.target as HTMLDivElement;
                const parent = target.parentNode as HTMLElement;

                if (parent.querySelector('.popover.show')?.classList.contains('show')) {
                    popoverTriggerEl.closest('.simsy-editor-block')?.classList.remove('edit');
                } else {
                    popoverTriggerEl.closest('.simsy-editor-block')?.classList.add('edit');
                }
            })

            return new bootstrap.Popover(popoverTriggerEl, config)
        })

        const frame: HTMLDivElement = event.target as HTMLDivElement;
        const baseFrame = document.getElementById('right-panel-frame');

        if (frame.id !== 'right-panel-frame') {
            return;
        }

        // the same but by creating the element and adding an event listener
        const closeBtn = document.createElement('div');
        closeBtn.classList.add('btn', 'btn-secondary');
        closeBtn.textContent = Translator.trans('simsy_cms.close');
        closeBtn.id = 'right-panel-close';
        closeBtn.addEventListener('click', () => {
            baseFrame.setAttribute('src', '');
            setTimeout(() => {
                baseFrame.innerHTML = '<div id="right-panel-loader">' + Translator.trans('simsy_cms.loading') + '</div>';
            }, 200);
        });
        frame.appendChild(closeBtn);
    })
}