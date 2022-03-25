// Represents a generic result received from the API.
export class Result {
	constructor(data, statusText = "UNKNOWN") {
		this.status = statusText;
		Object.assign(this, data);  // Copies all properties from data over to this.
	}
}

// Represents a single row retrieved from a database table.
export class Item extends Result {
	constructor(data) {
		if (data instanceof Item) return data;
		super(data);
		this.id = data?.id ?? null;
		this.apiName = (data.name ?? "ERROR").toLowerCase();
	}
}

// Represents a collection of items.
export class ItemList extends Result {
	constructor(data, type) {
		if (data instanceof ItemList) return data;
		super(data);
		this.items = data?.items ?? [data ?? {}];
		this.convertList(type);
	}

	// Convert all the items in a list into objects of a particular type.
	convertList(type) {
		if (!type) return false;  // If type is null, don't do anything
		this.items.forEach((item, i) => {
			this.items[i] = new type(item);
		});
	}
}