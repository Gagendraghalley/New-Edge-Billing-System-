// Errors.js
class Errors {
    constructor() {
      this.errors = {};
    }
    has(field) {
        return this.errors.hasOwnProperty(field);
    }

    any() {
        return Object.keys(this.errors).length > 0;
    }

    get(field) {
        if (this.errors[field]) {
            return this.errors[field][0];
        }
    }

    add(field, message) {
        if (!this.errors[field]) {
        this.errors[field] = [];
        }
        this.errors[field].push(message);
    }

    remove(field) {
        if (this.errors[field]) {
        delete this.errors[field];
        }
    }

    clear() {
        this.errors = {};
    }
    }

export default Errors;