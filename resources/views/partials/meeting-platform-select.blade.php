<div class="mb-3">
    <label for="meeting_platform" class="form-label">Plateforme de réunion</label>
    <select id="meeting_platform" name="meeting_platform" class="form-select @error('meeting_platform') is-invalid @enderror" required>
        <option value="" disabled {{ old('meeting_platform', $eventType->meeting_platform ?? '') ? '' : 'selected' }}>Sélectionnez une plateforme</option>
        @foreach(\App\Enums\MeetingPlatform::cases() as $platform)
            <option value="{{ $platform->value }}" 
                {{ old('meeting_platform', $eventType->meeting_platform ?? '') == $platform->value ? 'selected' : '' }}
                data-icon="{{ $platform->icon() }}">
                {{ $platform->label() }}
            </option>
        @endforeach
    </select>
    @error('meeting_platform')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3" id="meeting_link_container" style="{{ old('meeting_platform', $eventType->meeting_platform ?? '') == 'custom' ? '' : 'display: none;' }}">
    <label for="meeting_link" class="form-label">Lien de réunion personnalisé</label>
    <input type="url" id="meeting_link" name="meeting_link" class="form-control @error('meeting_link') is-invalid @enderror" 
        value="{{ old('meeting_link', $eventType->meeting_link ?? '') }}" 
        placeholder="https://exemple.com/reunion">
    @error('meeting_link')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="form-text text-muted">Entrez l'URL complète de votre réunion personnalisée.</small>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const meetingPlatformSelect = document.getElementById('meeting_platform');
    const meetingLinkContainer = document.getElementById('meeting_link_container');
    
    // Fonction pour afficher/masquer le champ de lien personnalisé
    function toggleMeetingLinkField() {
        if (meetingPlatformSelect.value === 'custom') {
            meetingLinkContainer.style.display = '';
        } else {
            meetingLinkContainer.style.display = 'none';
        }
    }
    
    // Écouter les changements sur le sélecteur de plateforme
    meetingPlatformSelect.addEventListener('change', toggleMeetingLinkField);
    
    // Initialiser l'état au chargement de la page
    toggleMeetingLinkField();
});
</script>
