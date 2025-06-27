@foreach($users as $user)
  <tr>
    <td>{{ $user->name }}</td>
    <td>
      <form method="POST" action="{{ route('admin.users.promote', $user) }}">
        @csrf

        <select name="role" onchange="this.form.submit()">
          <option value="" disabled selected>Choose…</option>
          @foreach($roles as $role)
            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
          @endforeach
        </select>

        {{-- Plain button to test visibility --}}
        <button type="submit">Change Role</button>
      </form>
    </td>
  </tr>
@endforeach
@foreach($users as $user)
  <tr>
    <td>{{ $user->name }}</td>
    <td>
      <form method="POST" action="{{ route('admin.users.promote', $user) }}">
        @csrf

        <select name="role" onchange="this.form.submit()">
          <option value="" disabled selected>Choose…</option>
          @foreach($roles as $role)
            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
          @endforeach
        </select>

        {{-- Plain button to test visibility --}}
        <button type="submit">Change Role</button>
      </form>
    </td>
  </tr>
@endforeach
