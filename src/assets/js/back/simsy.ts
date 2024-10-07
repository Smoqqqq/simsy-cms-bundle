declare var Translator; // Define the Translator variable that is included in the layout.html.twig file

import '../../styles/back/simsy.scss';

import './page';
import '@hotwired/turbo';
import 'bootstrap';
import {TurboBeforeFrameRenderEvent, TurboFrameRenderEvent} from "@hotwired/turbo";

addEventListener('turbo:load', () => {
    handleFrameRendering();
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