@if (!app()->isProduction())
    <div

        class="flex items-center content-center justify-center text-xs w-full px-4 py-2 dark:text-white bg-[rgb(76,175,80)]  backdrop-blur print:hidden  dark:ring-white/10">
        <div>
            🚀 L'application est en mode test. Les données ne sont pas fiables et peuvent être effacées à tout moment.
            Amusez-vous! 🚧
        </div>
    </div>
@endIf
