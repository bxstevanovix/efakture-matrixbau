<div class="btn-group" role="group">
    <button type="button" class="btn btn-sm btn-outline-primary edit-project-btn" data-id="{{ $entity->id }}" title="{{ __('Uredi projekat') }}">
        <i class="fas fa-edit"></i>
    </button>
</div>

<style>
    .btn-group {
        display: flex;
        gap: 4px;
    }
    
    .btn-outline-primary:hover {
        background-color: #667eea;
        color: white;
        border-color: #667eea;
    }
</style>

<script>
    $(document).on('click', '.edit-project-btn', function() {
        const projectId = $(this).data('id');
        // {{ __('Za budućnost - otvori modal za uređivanje') }}
        console.log('Edit project:', projectId);
    });
</script>
