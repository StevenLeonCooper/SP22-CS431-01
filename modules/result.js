// Represents a generic result received from the API.
export class Result {
	constructor(data) {
		this.status = "UNKNOWN";
		Object.assign(this, data);  // Copies all properties from data over to this.
	}
}

// Represents a single row retrieved from a database table.
export class Item extends Result {
	constructor(data) {
		if (data instanceof Item) return data;
		super(data);
		this.id = data?.id ?? null;
		this.apiName = (data?.name ?? "ERROR").toLowerCase();
	}
}

// Represents a collection of items.
export class ItemList extends Result {
	constructor(data, type) {
		if (data instanceof ItemList) return data;
		super(data);
		this.items = data?.items ?? [data ?? {}];
		this.itemType = type;
		this.convertList(type);
	}

	add(item){
        this.items.push(new this.itemType(item));
    }

	remove(key, value){
        let filteredList = this.items.filter(item => item[key] != value);
        this.items = filteredList;
    }

    replace(key, value, newItem){
        this.items.forEach((item, index)=>{
            if(item[key] == value){
                this.items[index] = newItem;
            }
        });
    }

	// Convert all the items in a list into objects of a particular type.
	convertList(type) {
		if (!type) return false;  // If type is null, don't do anything
		this.items.forEach((item, i) => {
			this.items[i] = new type(item);
		});
	}
}