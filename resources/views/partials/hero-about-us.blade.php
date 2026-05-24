{{-- ========================
     QUIÉNES SOMOS SECTION
     ======================== --}}
<section class="who-we-are-section">
        {{-- Encabezado --}}
        <div class="wwa-header">
            <h2 class="wwa-title">{{ __('messages.who_we_are') }}</h2>
            <p class="wwa-subtitle">
                 {{ __('messages.who_we_are_text_1') }}
            </p>
            <p class="wwa-subtitle">
                {{ __('messages.who_we_are_text_2') }}
            </p>
        </div>
  
</section>

{{-- ========================
     MISIÓN Y VISIÓN
     ======================== --}}
<section class="mission-vision-section">
    <div class="mv-container">

        <div class="mv-header">
            <h2 class="wwa-title">    {{ __('messages.mission_vision') }}
</h2>
        </div>

        <div class="mv-cards">
            {{-- Misión --}}
            <div class="mv-card">
                <div class="mv-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <circle cx="12" cy="12" r="6" />
                        <circle cx="12" cy="12" r="2" />
                    </svg>
                </div>
                <h3 class="mv-card-title">{{ __('messages.mission') }}</h3>
                <p class="mv-card-text">
                    {{ __('messages.mission_text') }}
                </p>
                <div class="mv-card-accent"></div>
            </div>

            {{-- Visión --}}
            <div class="mv-card">
                <div class="mv-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </div>
                <h3 class="mv-card-title"> {{ __('messages.vision') }}</h3>
                <p class="mv-card-text">
                     {{ __('messages.vision_text') }}
                </p>
                <div class="mv-card-accent"></div>
            </div>

        </div>

    </div>
</section>