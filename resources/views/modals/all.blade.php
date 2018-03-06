@includeWhen(auth()->check() && auth()->user()->confirmed, 'modals.new-issue')
@include('modals.login')
@include('modals.register')