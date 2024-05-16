// Sử dụng class hoặc Object trong ES6 để làm đẹp lại đoạn code
const user = {
    name: 'John',
    age: 30,

    displayUser() {
        console.log(this.name + ' is ' + this.age + ' years old.');
    },

    updateUser(newName, newAge) {
        this.name = newName;
        this.age = newAge;
    }
}

user.displayUser();
user.updateUser('Jane', 25);
user.displayUser();