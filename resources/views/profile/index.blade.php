@extends('../layouts/default')

@section('title', 'Profile')
@section('content')

<table class="table">
    <colgroup width="30%" />
    <colgroup width="70%" />
    <tbody>
        <tr>
            <th>name</th>
            <td>{{{ $profile->name }}}</td>
        </tr>
        <tr>
            <th>E-mail</th>
            <td>{{{ $profile->email }}}</td>
        </tr>
        <tr>
            <th>Password</th>
            <td>********</td>
        </tr>
    </tbody>
</table>

<div class="text-right">
    <a href="{{ url('/profile/edit') }}" class="btn btn-default">プロフィールを編集する</a>
</div>


<hr />

<h2>退会する</h2>
<div class="text-center">
    <a href="{{ url('/withdraw') }}" class="btn btn-danger">退会する</a>
</div>

@endsection
