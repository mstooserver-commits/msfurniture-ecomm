@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-[#17351d]/20 bg-[#fbfcf8] text-[#17351d] focus:border-[#5f8c52] focus:ring-[#5f8c52] rounded-none shadow-none px-4 py-3']) }}>
