import DragSortList from "./DragSortList";

class DragSortListFactory {
    constructor() { }
    create(dragContainerId, dragItemClass) {
        return new DragSortList(dragContainerId, dragItemClass);
    }
}

export default DragSortListFactory;
