export class Result {
	constructor(data) {
		if (data instanceof Result) return data;
		this.staus = data.status ?? "UNKNOWN";
		if (Array.isArray(data)) {
			this.items = data.items;
		} else {
			Object.assign(this, data);  // Copies all properties from data over to this.
		}
	}
}

export class Item extends Result {
	constructor(data) {
		if (data instanceof Item) return data;
		super(JSON.parse(JSON.stringify(data)));
		this.id = data.id ?? null; //Why is this needed if super does: Object.assign(this, data);
	}
}

export class ItemList extends Result {
	constructor(data, type) {
		if (data instanceof ItemList) return data;
		super(JSON.parse(JSON.stringify(data)));
		this.items = data.items ?? [];
		this.convertList(type);
	}

	convertList(type) {
		if (!type) return false;  // maybe if type is null, don't do anything
		this.items.forEach((item, i) => {
			this.items[i] = new type(item);
		});
	}
}