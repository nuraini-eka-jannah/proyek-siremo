<h1>Selamat Datang Admin, {{ Auth::user()->nama_lengkap }}</h1>
<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit">Logout</button>
</form>