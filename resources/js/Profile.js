export default class Profile{
    constructor(user){
        this.user = user;
        //console.log(this.user)
    }
    currentUser(){
        return this.user;
    }
}
