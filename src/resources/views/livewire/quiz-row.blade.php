<tr>
    @foreach($translations as $translation)
        <td id="{{ $translation->language_id }}">
            @if(property_exists($translation, 'word_name'))
                {{ $translation->word_name }}
            @else
                <input type="text" class="form-control" id="guesses.{{ $translation->word_id }}"
                       wire:model="guesses.{{ $translation->word_id }}"
                       style="background-color:
                          @if(isset($guess_results[$translation->word_id]) && $guess_results[$translation->word_id] === 'correct')
                              #28a745
                          @elseif(isset($guess_results[$translation->word_id]) && $guess_results[$translation->word_id] === 'incorrect')
                              #dc3545
                          @endif;">
            @endif
        </td>
    @endforeach
    <td>
        @if(empty($guess_results))
            <button type="submit" class="btn btn-primary btn-sm" wire:click="checkTranslation">
                <span class="fas fa-check" aria-hidden="true"></span>
            </button>
        @endif
    </td>
</tr>
