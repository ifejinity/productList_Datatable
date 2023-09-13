@extends('layout.layout')

@section('body')
    <div class="w-full p-5 bg-white shadow-lg rounded-lg">
        {{-- btn add --}}
        <button class="btn btn-primary btn-active mb-5" id="showAddModal">Add Product</button>
        {{-- product table --}}
        <div>
            <table class="table rounded-none" id="tableProduct">
                <thead>
                    <tr>
                        <th>Product id</th>
                        <th>Product name</th>
                        <th>Price</th>
                        <th>Stocks</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="data">
                    {{-- data go here --}}
                </tbody>
            </table>
        </div>
        {{-- modal add --}}
        <div class="fixed w-full min-h-screen bg-black/30 z-[2] top-0 left-0 hidden justify-center items-center" id="addModal">
            <div class="card w-full max-w-[500px] bg-base-100 shadow-xl">
                <div class="card-body">
                    <div class="card-actions justify-between items-center">
                        <h1 class="text-indigo-600 text-[24px] font-[700]">Add product</h1>
                        <button class="btn btn-square btn-sm" id="hideAddModal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <form class="flex flex-col gap-1">
                        <input type="number" name="id" placeholder="Product ID" class="input input-bordered w-full" />
                        <span name="errorMessageId" class="text-[14px] text-red-500 mb-2"></span>
                        <input type="text" name="name" placeholder="Product name" class="input input-bordered w-full" />
                        <span name="errorMessageName" class="text-[14px] text-red-500 mb-2"></span>
                        <input type="text" name="price" placeholder="Product price" class="input input-bordered w-full" />
                        <span name="errorMessagePrice" class="text-[14px] text-red-500 mb-2"></span>
                        <input type="number" name="stocks" placeholder="Stocks" class="input input-bordered w-full" />
                        <span name="errorMessageStocks" class="text-[14px] text-red-500 mb-2"></span>
                        <button type="submit" class="btn btn-primary btn-active" id="create">Create</button>
                    </form>
                </div>
            </div>
        </div>
        {{-- creating --}}
        <div class="fixed w-full min-h-screen bg-black/30 z-[2] top-0 left-0 hidden flex-col gap-2 justify-center items-center" id="creating">
            <span class="loading loading-spinner loading-lg text-primary"></span>
            <p class="text-white">Creating</p>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            // show add modal
            $('#showAddModal').click(function () { 
                $('#addModal').addClass('flex').removeClass('hidden');
            });
            // hide add modal
            $('#hideAddModal').click(function () { 
                $('#addModal').addClass('hidden').removeClass('flex');
            });
            // data table function
            function getProduct() {
                $('#tableProduct').DataTable({
                    processing: true,
                    serverSide: true,
                    stateSave: true,
                    ajax: "{{ route('product') }}",
                    method: "GET",
                    columns: [
                        { data: 'product_id', name: 'product_id' },
                        { data: 'product_name', name: 'product_name' },
                        { data: 'price', name: 'price' },
                        { data: 'stocks', name: 'stocks' },
                        {
                            render: function(data, type, full, meta) {
                                return `
                                <button type='button' value='${full.id}' class='btn btn-error btn-xs delete btn-active'>Delete</button>
                                <button type='button' value='${full.id}' class='btn btn-info btn-xs edit btn-active'>Edit</button>`;
                            }
                        },
                    ]
                });
            }
            // run getrpoduct function on load
            getProduct();
            // delete confirmation
            const actions = document.querySelector('.data');
            actions.addEventListener('click', (event)=>{
                event.preventDefault();
                if(event.target.classList.contains('delete')) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // window.location.href = event.target.href;
                            $.ajax({
                                type: "GET",
                                url: "{{ route('delete') }}",
                                data: {id:event.target.value},
                                success: function (response) {
                                    // toast success
                                    success(response.message);
                                    // destroy data datatable and reinitialized
                                    $('#tableProduct').DataTable().destroy();
                                    getProduct();
                                }
                            });
                        }
                    })
                }
                if (event.target.classList.contains('edit')) {

                }
            })
            // add product
            $("#create").click(function (e) { 
                e.preventDefault();
                // set value for data
                var id = $('input[name=id]').val();
                var name = $('input[name=name]').val();
                var price = $('input[name=price]').val();
                var stocks = $('input[name=stocks]').val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('create') }}",
                    data: {product_id:id, product_name:name, price:price, stocks:stocks},
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    beforeSend: function () {
                        // show creating display and reset error messages
                        $('#creating').addClass('flex').removeClass('hidden');
    
                    },
                    error: function (error) { 
                        console.log(error);
                    },
                    success: function (response) {
                        console.log(response)
                        if(response.status == 'success') {
                            setTimeout(() => {
                                // toast success
                                success(response.message)
                                // reset inputs value
                                $('input[name=id]').val("");
                                $('input[name=name]').val("");
                                $('input[name=price]').val("");
                                $('input[name=stocks]').val("");
                                // hide modal add and creating display
                                $('#creating').addClass('hidden').removeClass('flex');
                                $('#addModal').addClass('hidden').removeClass('flex');
                                // destroy data datatable and reinitialized
                                $('#tableProduct').DataTable().destroy();
                                getProduct();
                            }, 1000);
                        } else {
                            setTimeout(() => {
                                // toast error
                                error(response.message)
                                // hide creating modal
                                $('#creating').addClass('hidden').removeClass('flex');
                            }, 1000);
                        }
                    }
                });
            });
        });
    </script>
@endsection