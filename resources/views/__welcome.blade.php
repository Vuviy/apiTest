<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>

<div class="d-flex justify-content-center mt-5 flex-column" style="width: 50%; margin-right: auto; margin-left: auto">




    <div class="mt-5">
        <div class="d-flex justify-content-center">
            <button class="btn-list btn btn-primary btn-lg m-3">Show more</button>

            <div class="mb-3 m-3">
                <label class="form-label">Page</label>
                <input type="number" name="page" class="form-control">
            </div>

            <div class="mb-3 m-3">
                <label class="form-label">Count</label>
                <input type="number" value="6" name="count" class="form-control">
            </div>

            <div class="mb-3 m-3">
                <label class="form-label">Offset</label>
                <input type="number" name="offset" class="form-control">
            </div>
        </div>
        <div class="container mt-3">
{{--            <div class="item border border-secondary p-3 mt-3">--}}
{{--                <p>Name: <span>Rick</span></p>--}}
{{--                <p>Phone: <span>+3898598695865</span></p>--}}
{{--                <p>Email: <span>fgcfg@vgvg.cdsc</span></p>--}}
{{--                <p>Position: <span>Docktor</span></p>--}}
{{--                <img width="70px" height="70px" src="{{asset('storage/images/'). '/dchdvchjd.jpg'}}" alt="">--}}
{{--            </div>--}}
        </div>
    </div>

    <div class="mt-5 mb-5">
{{--    <form action="{{route('send')}}" method="post" enctype="multipart/form-data">--}}
{{--    <form>--}}
{{--        @csrf--}}
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Name</label>
            <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email</label>
            <input type="text" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>
        <div class="mb-3">
            <label for="exampleInpu" class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" id="exampleInpu">
        </div>
        <div class="mb-3">
            <label for="exampleInpuds" class="form-label">Position id</label>
{{--            <input type="number" name="position_id" class="form-control" id="exampleInpuds">--}}

            <select class="form-select" name="position_id" aria-label="Default select example">
                @foreach($positions as $position)
                    <option value="{{$position->id}}">{{$position->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="exampleInpudsdcs" class="form-label">Photo</label>
            <input type="file" name="photo" class="form-control" id="exampleInpudsdcs">
        </div>
            <button class="btn btn-primary btn-create">Submit</button>
{{--    </form>--}}


        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>

<script>

    let btnList = document.querySelector('.btn-list');
    let container = document.querySelector('.container');
    let page = document.querySelector('input[name="page"]');
    let count = document.querySelector('input[name="count"]');
    let offset = document.querySelector('input[name="offset"]');
    // let url = 'http://localhost:8000/apiTest/public/api/v1/users'
    let url = window.location.href + 'api/v1/users'

    btnList.addEventListener('click', function (){

        console.log(url);


        container.innerHTML = ''
        if(page.value || count.value || offset.value){
            url += '?';
        }
        if(page.value != 0){
            url += 'page=' + page.value + '&'
        }
        if(count.value != 0){
            url += 'count=' + count.value + '&'
        }
        if(offset.value != 0){
            url += 'offset=' + offset.value + '&'
        }

        fetch(url)
            .then(function(response) {

                if(!response.ok){
                    url = window.location.href + 'api/v1/users'
                    page.value = 0
                    count.value = 0
                    offset.value = 0
                    console.log(response.ok)
                }
                return response.json();
            })
            .then(function(data) {
                data.users.forEach(item => {
                    let listItem = document.createElement('div');
                    listItem.classList.add('item')
                    listItem.classList.add('border')
                    listItem.classList.add('border-secondary')
                    listItem.classList.add('p-3')
                    listItem.classList.add('mt-3')

                    let idP = document.createElement('p');
                    let idSpan = document.createElement('span');
                    idP.textContent = 'Id: ';
                    idSpan.textContent = item.id;
                    idP.appendChild(idSpan);
                    listItem.appendChild(idP);

                    let nameP = document.createElement('p');
                    let nameSpan = document.createElement('span');
                    nameP.textContent = 'Name: ';
                    nameSpan.textContent = item.name;
                    nameP.appendChild(nameSpan);
                    listItem.appendChild(nameP);

                    let phoneP = document.createElement('p');
                    let phoneSpan = document.createElement('span');
                    phoneP.textContent = 'Phone: ';
                    phoneSpan.textContent = item.phone;
                    phoneP.appendChild(phoneSpan);
                    listItem.appendChild(phoneP);

                    let emailP = document.createElement('p');
                    let emailSpan = document.createElement('span');
                    emailP.textContent = 'Email: ';
                    emailSpan.textContent = item.email;
                    emailP.appendChild(emailSpan);
                    listItem.appendChild(emailP);

                    let positionP = document.createElement('p');
                    let positionSpan = document.createElement('span');
                    positionP.textContent = 'Position: ';
                    positionSpan.textContent = item.position;
                    positionP.appendChild(positionSpan);
                    listItem.appendChild(positionP);

                    let photo = document.createElement('img');
                    let src = item.photo
                    if(item.photo.startsWith('images')){
                        src = window.location.href + 'storage/' + item.photo;
                    }
                    photo.setAttribute('src', src);
                    listItem.appendChild(photo);

                    container.appendChild(listItem);

                });

                let prev = document.createElement('a');
                prev.textContent = 'Prev';
                prev.setAttribute('href', data.links.prev_url);

                let next = document.createElement('a');
                next.textContent = 'Next';
                next.setAttribute('href', data.links.next_url);

                let divLinks = document.createElement('div');

                divLinks.appendChild(prev);
                divLinks.appendChild(next);
                container.appendChild(divLinks);

                url = window.location.href + 'api/v1/users'
                page.value = 0
                count.value = 0
                offset.value = 0

                if(data.success)
                {
                    // console.log('success')
                }
                else {
                    // console.log('server errors')
                }
            })
    })




    let btnCreate = document.querySelector('.btn-create')
    btnCreate.addEventListener('click', function (){
        var formData = new FormData(); // file from input type='file'
        var fileField = document.querySelector('input[type="file"]');
        formData.append('position_id', document.querySelector('select[name="position_id"]').value);
        formData.append('name', document.querySelector('input[name="name"]').value);
        formData.append('email', document.querySelector('input[name="email"]').value);
        formData.append('phone', document.querySelector('input[name="phone"]').value);
        formData.append('photo', fileField.files[0]);


        let token = ''
        let urlToken = window.location.href + 'api/v1/token'

            fetch(urlToken)
                .then(function(response) {
                    // console.log(response);

                    return response.json();
                })
                .then(function(data)
                {
                    token = data.token
                })
                .catch(function(error)
                {
                    // proccess network errors
                });

        setTimeout(() => {

            fetch(window.location.href + 'api/v1/users',
                { method: 'POST', body: formData, headers:
                        {'Token': token},
                })
                .then(function(response)
                {return response.json();})
                .then(function(data)
                {
                    if(data.success == false){
                        // let message = ''
                        // data.fails.forEach(item => {
                        //     console.log(item);
                        //     message += item
                        // })
                        // console.log(data.message);
                        alert(data.message)
                    }
                    // console.log(data);
                    if(data.success)
                    {
                        alert(data.message)
                        document.querySelector('input[type="file"]').value = ''
                         document.querySelector('input[name="position_id"]').value = ''
                         document.querySelector('input[name="name"]').value = ''
                         document.querySelector('input[name="email"]').value = ''
                         document.querySelector('input[name="phone"]').value = ''
                        // process success response
                    } else {
                        // proccess server errors
                    }
                })
                .catch(function(error)
                {
                    //proccess network errors
                });

        }, 1100);

    })







</script>
</body>
</html>
