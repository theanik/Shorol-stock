<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Category <span class="p-1 mb-2 bg-primary text-white">{{total}}</span></h4>
                        <div class="card-tools" style="position: absolute;right: 1rem;top: .5rem,color:#fff;">
                        <button @click="csvExport(csvData)" class="btn btn-primary">Export as CVS</button>
                        <button type="button" class="btn btn-success" @click.prevent="create">
                            Add New Category
                            <i class="fas fa-plus"></i>
                        </button>
                        
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-2">
                                <strong>Search By :</strong>
                                </div>
                                <div class="col-md-3">
                                    Category Name
                                </div>
                                <div class="col-md-5">
                                <input v-model="query" type="text" class="form-control" placeholder="Search">
                                </div>
                                <div class="col-md-2">
                                     <button type="button" class="btn btn-primary" >
                                        Reset Search
                                        <i class="fas fa-sync"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- table  -->
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-striped">
                                <thead>
                                    <tr>
                                    <th scope="col">Category Id</th>
                                    <th scope="col">Category Name</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-show="categories.length" v-for="category in categories" :key="category.id">
                                        <th scope="row">{{ category.id }}</th>
                                        <td>{{ category.name }}</td>
                                        <td>{{ category.created_at | mydate}}</td>
                                        <td>
                                            <button type="button" @click.prevent="edit(category)" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" @click.prevent="destroy(category)" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-show="!categories.length">
                                        <td colspan="6">
                                            <div class="alert alert-danger" role="alert">Sorry :( No data found.</div>
                                        </td>
                                    </tr>
                                    
                                </tbody>
                                </table>
                                <pagination-component v-if="pagination.last_page > 1"
                                    :pagination="pagination"
                                    :offset="5"
                                    @paginate="query === '' ? getData() : searchData()"></pagination-component>
                        </div>

                        <!-- end table -->
                    </div>
                </div>
            </div>
            <!-- modal -->
            <div class="modal fade" id="categoryModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            
                            <h5 class="modal-title" id="exampleModalLongTitle">{{ editMode ? "Edit" : "Add New"}}  Category</h5>
                            
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                         <form  @submit.prevent=" editMode ? update() : store()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                          <alert-error :form="form" message="There were some problems with your input."></alert-error>
                                <div class="form-group">
                                <label>Category Name</label>
                                <input v-model="form.name" type="text" name="name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                                </div>

                              

                           
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button :disabled="form.busy" type="submit" class="btn btn-primary">Save</button>
                        </div>
                         </form>

                        </div>
                    </div>
                </div>
                <!-- end moal -->
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                query : '',
                editMode : false,
                categories : [],
                form : new Form({
                    id : '',
                    name : ''
                }),
                pagination :{
                    current_page : 1,
                },
                currentUser : null,
                total : null,
                data :{
                    exportdata : []
                }
            }
        },
         watch:{
            query:function(newQ,old) {
                    if(newQ === ''){
                        this.getData();
                    }else{
                        this.searchData();
                    }
                
            }
        },
        computed: {
            csvData() {
            return this.exportdata.map(item => ({
                ...item,
                //id: item.id, //item.address.city,
                //name: 'компания'//item.company.name
            }));
            }
        },
        mounted() {
            console.log('Component mounted.')
            this.getData()
             axios.get("api/allcategory")
                    .then(res=>{
                       this.exportdata = res.data.data
                       //console.log(res.data.data)
                    })
                
        },
        methods:{
            csvExport(arrData) {
                    let csvContent = "data:text/csv;charset=utf-8,";
                    csvContent += [
                        Object.keys(arrData[0]).join(";"),
                        ...arrData.map(item => Object.values(item).join(";"))
                    ]
                        .join("\n")
                        .replace(/(^\[)|(\]$)/gm, "");

                    const data = encodeURI(csvContent);
                    const link = document.createElement("a");
                    link.setAttribute("href", data);
                    link.setAttribute("download", "category.csv");
                    link.click();
                    
                },
            getData(){
                this.$Progress.start()
                axios.get('/api/category?page='+this.pagination.current_page)
                    .then(res=>{
                         //console.log(res)
                        this.categories = res.data.data
                        this.pagination = res.data.meta
                        this.total = res.data.meta.total
                        this.$Progress.finish()
                    })
                    .catch(e=>{
                        console.log(e)
                        this.$Progress.fail()
                    })
                },
                create(){
                     this.form.reset()
                    this.form.clear()
                    this.editMode = false
                    $('#categoryModalLong').modal('show');
                },
                store(){
                    this.$Progress.start()
                    this.form.busy = true
                    this.form.post('/api/category')
                        .then(res=>{
                            this.getData()
                            $('#categoryModalLong').modal('hide');
                            if(this.form.successful){
                            this.$Progress.finish();
                            this.$snotify.success('Category Add Successfully','Success')
                            }else{
                                this.$Progress.fail()
                                this.$snotify.error('Something wend wrong. Try aging','Error')
                            }
                        })
                        .catch(e=>{
                            console.log(e)
                            this.$Progress.fail()
                        })
                },
                edit(category){
                    this.editMode = true
                    this.form.reset()
                    this.form.clear()
                    this.form.fill(category)
                    $('#categoryModalLong').modal('show');
                },
                update(){
                    this.$Progress.start()
                    this.form.busy = true
                    this.form.put('/api/category/'+this.form.id)
                        .then(res=>{
                            this.getData()
                            $('#categoryModalLong').modal('hide');
                            if(this.form.successful){
                                this.$Progress.finish();
                                this.$snotify.success('Category Update Successfully','Success')
                            }else{
                                this.$Progress.fail()
                                this.$snotify.error('Something wend wrong. Try aging','Error')
                            }
                        })
                        .catch(e=>{
                            this.$Progress.fail()
                            console.log(e)
                        })
                },
                destroy(category){

                this.$snotify.clear()
                this.$snotify.confirm("You will not be able to recover this data!","Are you sure?", {
                        showProgressBar: false,
                        closeOnClick: false,
                        pauseOnHover: true,
                        buttons:[
                            {
                                text : "Yes",
                                action : toast => {
                                    this.$snotify.remove(toast.id)
                                    //console.log(customer)
                                    this.$Progress.start()
                                    axios.delete('/api/category/'+category.id)
                                    .then(res=>{
                                        this.getData()
                                            this.$Progress.finish();
                                            this.$snotify.success('Category Delete Successfully','Success')
                                       
                                    })
                                    .catch(e=>{
                                        this.$Progress.fail()
                                        console.log(e)
                                    })
                                },
                                bold : true
                            },
                            {
                                text : "No",
                                action : toast => {
                                    this.$snotify.remove(toast.id)
                                },
                                bold : true
                            }
                            
                        ]
                    })
                },searchData(){
                    this.$Progress.start()
                    axios.get('/api/search/category/'+this.query+'?page='+this.pagination.current_page)
                        .then(res=>{
                            this.categories = res.data.data
                            this.pagination = res.data.meta
                            this.$Progress.finish()
                        })
                        .catch(e=>{
                            console.log(e)
                            this.$Progress.fail()
                        })
                    },
                    reload(){
                    this.getData()
                    this.query = ''
                    
                    this.$snotify.info('Search reset', {
                        timeout: 1000,
                        showProgressBar: false,
                        closeOnClick: true,
                        pauseOnHover: false
                        });
                    },
                
            }
        }

  

</script>
