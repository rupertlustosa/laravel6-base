import SaleModel from "./SaleModel";

class NozzleModel {

    constructor() {
        this.id = null;
        this.sales = [new SaleModel()];
    }
}

export default NozzleModel;
