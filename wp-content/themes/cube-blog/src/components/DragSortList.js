const DRAGGING_CLASS = 'dragging';

class DragSortList {
    constructor(dragContainer, dragItemClass) {
        this.dragContainer = dragContainer;
        this.dragItemClass = dragItemClass;
        this.draggingClass = DRAGGING_CLASS;
        this.draggedItem = null;
        this._setupListeners();
    }
    _setupListeners() {
        this.dragContainer.addEventListener('dragover', this.handleDragover.bind(this));
        this.dragContainer.addEventListener('dragstart', this.handleDragStart.bind(this));
        this.dragContainer.addEventListener('dragend', this.handleDragEnd.bind(this));
    }
    handleDragover(evnt) {
        evnt.preventDefault();
        let afterElement = this.getDragAfterElement(evnt.clientY);
        let currentElement = document.querySelector(`.${this.draggingClass}`);
        if (afterElement == null) {
            this.dragContainer.appendChild(this.draggedItem);
        } else {
            this.dragContainer.insertBefore(this.draggedItem, afterElement);
        }
    }
    handleDragStart(evnt) {
        this.draggedItem = evnt.target;
        setTimeout(() => {
            this.draggedItem.classList.add(this.draggingClass);
        }, 0);
    }
    handleDragEnd(evnt) {
        setTimeout(() => {
            this.draggedItem.classList.remove(this.draggingClass);
            this.draggedItem = null;
        }, 0);
    }
    getDragAfterElement(yValue) {
        let draggableElements = [...this.dragContainer.querySelectorAll(`div.${this.dragItemClass}:not(.${this.draggingClass})`)];
        let reduceFunction = function(closest, nextElement) {
            let box = nextElement.getBoundingClientRect();
            let offset = yValue - box.top - box.height / 2; // y distance cursor is below middle of this element
            if ( offset < 0 && offset > closest.offset) {
                return { offset: offset, element: nextElement, };
            } else {
                return closest;
            }
        }
        let afterElement = draggableElements.reduce(reduceFunction, { offset: Number.NEGATIVE_INFINITY }).element;
        return afterElement;
    }
}

export default DragSortList;
