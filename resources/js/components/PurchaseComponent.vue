<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Purchase || Stock <span class="p-1 mb-2 bg-primary text-white">{{total}}</span></h4>
                        <div class="card-tools" style="position: absolute;right: 1rem;top: .5rem,color:#fff;">
                         <button @click="csvExport(csvData)" class="btn btn-primary">Export as CVS</button>
                        <button type="button" class="btn btn-success" @click.prevent="create">
                            Purchase
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
                                <select v-model="queryFiled" class="form-control" id="fileds">
                                    <option value="product">Product</option>
                                    <option value="supplier">Supplier</option>
                                </select>
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
                                    <th scope="col">Stock Id</th>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Supplier</th>
                                    <th scope="col">Product Quantity</th>
                                    <th scope="col">Retail Price</th>
                                    <th scope="col">Sale Price</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="purchase in purchases" :key="purchase.id">
                                        <th scope="row">{{ purchase.id }}</th>
                                        <td><a @click.prevent="showProductDetails(purchase)"
                                        class="text-primary" style="cursor:pointer">{{ purchase.product.name }}</a></td>
                                        <td><a @click.prevent="showSupplierDetails(purchase)"
                                        class="text-primary" style="cursor:pointer">{{ purchase.supplier.name }}</a></td>
                                        <td>{{ purchase.product_quantity }}</td>
                                        <td>{{ purchase.retail_price }}</td>
                                        <td>{{ purchase.sale_price }}</td>
                                        
                                        <td>
                                            <button type="button" @click.prevent="show(purchase)" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" @click.prevent="edit(purchase)" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" @click.prevent="destroy(purchase)" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td v-show="!purchases.length" colspan="6">
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

                  <!-- Modal -->
                <div class="modal fade" id="ModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">

                            <h5 v-if="showMode == true" class="modal-title text-info" id="exampleModalLongTitle">Purchases Details for <strong>{{ showDetails.product.name }}</strong> </h5>
                            <h5 v-else class="modal-title text-primary" id="exampleModalLongTitle">{{ editMode ? "Edit" : "Add New"}}  Product</h5>
                            
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                         <form v-if="showMode == false" @submit.prevent=" editMode ? update() : store()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                          <alert-error :form="form" message="There were some problems with your input."></alert-error>
                                
                                <div class="form-group">
                                <label>Select Product </label>
                                    <select v-model="form.product_id" name="product_id" required 
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('product_id') }" placeholder="Select Category">
                                        <option v-for="product in products" :key="product.id" v-bind:value="product.id">{{ product.name }}</option>
                                    </select>
                                <has-error :form="form" field="product_id"></has-error>
                                </div>
                                <p v-if="form.errors.has('product_id')" class="text-primary">This Product Has Already Purchase, If You Need You Can Update Info !</p>

                                <div class="form-group">
                                <label>Select Supplier </label>
                                    <select v-model="form.supplier_id" name="supplier_id"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('supplier_id') }" placeholder="Select Subblier">
                                        <option v-for="supplier in suppliers" :key="supplier.id" v-bind:value="supplier.id">{{ supplier.name }}</option>
                                    </select>
                                <has-error :form="form" field="supplier_id"></has-error>
                                </div>
                                <div class="form-group">
                                <label>{{ editMode ? "New Quantity - thst's will add to old quantity" : "Product Quantity"}} </label>
                                    <input v-model="form.product_quantity" type="number" name="product_quantity" placeholder="Enter Product Quantity" 
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('product_quantity') }">
                                <has-error :form="form" field="product_quantity"></has-error>
                                </div>
                                <div class="form-group">
                                <label>Retail Price</label>
                                    <input v-model="form.retail_price" type="number" name="retail_price" placeholder="Enter Product Retail Price" 
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('retail_price') }">
                                <has-error :form="form" field="retail_price"></has-error>
                                </div>
                                <div class="form-group">
                                <label>Sale Price</label>
                                    <input v-model="form.sale_price" type="number" name="sale_price" placeholder="Enter Product Sale Price" 
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('sale_price') }">
                                <has-error :form="form" field="sale_price"></has-error>
                                </div>

                           
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button :disabled="form.busy" type="submit" class="btn btn-primary">Save</button>
                        </div>
                         </form>
                        <!-- show purchase details -->
                          <div v-if="showMode ==  true" class="modal-body">
                              <div class="card border-info mb-3">
                                <div class="card-body">

                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Product Name : </strong> {{ showDetails.product.name }}</li>
                                        <li class="list-group-item"> <strong>Category Name : </strong> {{ showDetails.product.cat_name }}</li>
                                        <li class="list-group-item"><strong>Product Code : </strong> {{ showDetails.product.code }}</li>
                                        <li class="list-group-item"><strong>Retail Price : </strong> {{ showDetails.retail_price }}</li>
                                        <li class="list-group-item"><strong>Sale Price : </strong> {{ showDetails.sale_price }}</li>
                                        <li class="list-group-item"><strong>Product Details : </strong> {{ showDetails.product.description }}</li>

                                        <li class="list-group-item"> <strong>Supplier Name : </strong> {{ showDetails.supplier.name }}</li>
                                        <li class="list-group-item"> <strong>Supplier Company : </strong> {{ showDetails.supplier.company_name }}</li>
                                        <li class="list-group-item"> <strong>Supplier Phone : </strong> {{ showDetails.supplier.phone }}</li>
                                        <li class="list-group-item"> <strong>Supplier E-mail : </strong> {{ showDetails.supplier.email }}</li>
                                        <li class="list-group-item"><strong>Created At : </strong> {{ showDetails.created_at | mydate }}</li>
                                        
                                    </ul>
                                </div>
                                </div>
                          </div>
                          <!-- show Single Product Details -->
                          <div v-if="showProMode ==  true" class="modal-body">

                              <div class="card border-info mb-3">
                                <div class="card-body">

                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Product Name : </strong> {{ showDetails.name }}</li>
                                        <li class="list-group-item"> <strong>Category Name : </strong> {{ showDetails.cat_name }}</li>
                                        <li class="list-group-item"><strong>Product Code : </strong> {{ showDetails.code }}</li>
                                        <li class="list-group-item"><strong>Created At : </strong> {{ showDetails.created_at | mydate }}</li>
                                        
                                    </ul>
                                </div>
                                </div>
                          </div>
                          <!-- show single Supplier Detais -->
                          <div v-if="showSupplierMode ==  true" class="modal-body">

                              <div class="card border-info mb-3">
                                <div class="card-body">

                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Supplier Name : </strong> {{ showDetails.name }}</li>
                                        <li class="list-group-item"><strong>Supplier Company : </strong> {{ showDetails.company_name }}</li>
                                        <li class="list-group-item"><strong>Phone : </strong> {{ showDetails.phone }}</li>
                                        <li class="list-group-item"><strong>E-mail : </strong> {{ showDetails.email }}</li>
                                        <li class="list-group-item"><strong>Created At : </strong> {{ showDetails.created_at | mydate }}</li>
                                        
                                    </ul>
                                </div>
                                </div>
                          </div>
                          <!--  -->
                        </div>
                    </div>
                </div>
            <!-- end modal  -->
            <!-- moadal 2  -->
            <div class="modal fade" id="ModalLong2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="exampleModalLongTitle">{{ showDetails.name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- show Single Product Details -->
                          <div v-if="showProMode ==  true" class="modal-body">

                              <div class="card border-info mb-3">
                                <div class="card-body">

                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Product Name : </strong> {{ showDetails.name }}</li>
                                        <li class="list-group-item"> <strong>Category Name : </strong> {{ showDetails.cat_name }}</li>
                                        <li class="list-group-item"><strong>Product Code : </strong> {{ showDetails.code }}</li>
                                        <li class="list-group-item"><strong>Created At : </strong> {{ showDetails.created_at | mydate }}</li>
                                        
                                    </ul>
                                </div>
                                </div>
                          </div>
                          <!-- show single Supplier Detais -->
                          <div v-if="showSupplierMode ==  true" class="modal-body">

                              <div class="card border-info mb-3">
                                <div class="card-body">

                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Supplier Name : </strong> {{ showDetails.name }}</li>
                                        <li class="list-group-item"><strong>Supplier Company : </strong> {{ showDetails.company_name }}</li>
                                        <li class="list-group-item"><strong>Phone : </strong> {{ showDetails.phone }}</li>
                                        <li class="list-group-item"><strong>E-mail : </strong> {{ showDetails.email }}</li>
                                        <li class="list-group-item"><strong>Created At : </strong> {{ showDetails.created_at | mydate }}</li>
                                        
                                    </ul>
                                </div>
                                </div>
                          </div>
                          <!--  -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        
                    </div>
                    </div>
                </div>
            </div>
            <!-- moadal 2 end -->
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                editMode : false,
                showMode : false,
                showProMode : false,
                showSupplierMode : false,
                query : "",
                queryFiled : "product",
                purchases : [],
                pagination:{
                    current_page : 1,
                },
                form : new Form({
                    id : '',
                    product_id : '',
                    supplier_id : '',
                    product_quantity : '',
                    retail_price : '',
                    sale_price : '',
                }),
                products : [],
                suppliers : [],
                showDetails : [],
                total : null,
                data :{
                    exportdata : []
                }

            }
        },
        computed: {
            csvData() {
            return this.exportdata.map(item => ({
                ...item,
            }));
            }
        },
        mounted() {
            console.log('Component mounted.')
            this.getData()
            this.getProduct()
            this.getSupplier()
            axios.get("api/allpurchase")
                    .then(res=>{
                       this.exportdata = res.data.data
                       
                    })
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
                    link.setAttribute("download", "purchases.csv");
                    link.click();
                    
                },
            getData(){
                this.$Progress.start()
                axios.get('/api/purchase?page='+this.pagination.current_page)
                    .then(res=>{
                        //console.log(res.data.data)
                        this.purchases = res.data.data
                        this.pagination = res.data.meta
                        this.total = res.data.meta.total
                        this.$Progress.finish()


                    })
                    .catch(e=>{
                        console.log(e)
                        this.$Progress.fail()
                    })
            },
            getProduct(){
                 axios.get('/api/allproduct')
                    .then(res=>{
                        this.products = res.data.data
                    })
                    .catch(e=>{
                        console.log(e)
                    })
            },
            getSupplier(){
                 axios.get('/api/allsupplier')
                    .then(res=>{
                        this.suppliers = res.data.data
                    })
                    .catch(e=>{
                        console.log(e)
                    })
            },
            create(){
                this.editMode = false
                this.showMode = false
                this.showProModem = false
                this.showSupplierMode = false
                
                this.form.reset()
                this.form.clear()
                $('#ModalLong').modal('show');
            },
            store(){
                this.$Progress.start()
                this.form.busy = true
                this.form.post('/api/purchase')
                    .then(res=>{
                        this.getData()
                        $('#ModalLong').modal('hide');
                        if(this.form.successful){
                            this.$Progress.finish();
                            this.$snotify.success('Purchase Successfull','Success')
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
            edit(purchase){
                this.showMode = false
                this.showProModem = false
                this.showSupplierMode = false
                this.editMode = true
                
                this.form.reset()
                this.form.clear()
                this.form.fill(purchase)
                $('#ModalLong').modal('show');
            },
            update(){
                this.$Progress.start()
                this.form.busy = true
                this.form.put('/api/purchase/'+this.form.id)
                    .then(res=>{
                        this.getData()
                        $('#ModalLong').modal('hide');
                        if(this.form.successful){
                            this.$Progress.finish();
                            this.$snotify.success('Purchase Details Update Successfully','Success')
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
            destroy(purchase){

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
                                    //console.log(product)
                                    this.$Progress.start()
                                    axios.delete('/api/purchase/'+purchase.id)
                                    .then(res=>{
                                        this.getData()
                                            this.$Progress.finish();
                                            this.$snotify.success('Purchases Detetaed!!','Success')
                                       
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
            },
            show(purchase){
                this.editMode = false
                this.showSupplierMode = false
                this.showProMode = false
                this.showMode = true
                
                $('#ModalLong').modal('show');
                //console.log(product)
                this.showDetails = purchase
            },
            showProductDetails(purchase){
                this.editMode = false
                this.showMode = false
                this.showSupplierMode = false
                this.showProMode = true
                $('#ModalLong2').modal('show');
                let id = purchase.product_id;
                //console.log(id)
                axios.get('/api/product/'+id)
                .then(res=>{
                        this.showDetails = res.data.data
                       // console.log(res.data.data)
                    })
                    .catch(e=>{
                        console.log(e)
                    })
            },
            showSupplierDetails(purchase){
                this.editMode = false
                this.showMode = false
                this.showProMode = false
                this.showSupplierMode = true
                $('#ModalLong2').modal('show');
               let id = purchase.supplier_id;
                //console.log(id)
                axios.get('/api/supplier/'+id)
                .then(res=>{
                        this.showDetails = res.data.data
                        //console.log(res.data.data)
                    })
                    .catch(e=>{
                        console.log(e)
                    })
            },
            searchData(){
                this.$Progress.start()
                axios.get('/api/search/purchase/'+this.queryFiled+'/'+this.query+'?page='+this.pagination.current_page)
                    .then(res=>{
                        //console.log(res.data.data)
                        this.purchases = res.data.data
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
             this.queryFiled = 'name'
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
