<div class="flex justify-center">
  <nav class="text-primary flex items-stretch h-10 gap-2">
    @php
      ob_start();
      echo '<span class="flex text-xl items-center h-full px-2 text-black bg-white no-underline md:px-3 hover:text-primary">';
    @endphp
    <x-icon name="chevron-left" classes="w-4 h-4" />
    @php
      echo '</span>';
      $prevIconHtml = ob_get_clean();

      ob_start();
      echo '<span class="flex text-xl items-center h-full px-2 text-black bg-white no-underline md:px-3 hover:text-primary">';
    @endphp
    <x-icon name="chevron-right" classes="w-4 h-4" />
    @php
      echo '</span>';
      $nextIconHtml = ob_get_clean();

      // Previous page link
      if ($paged > 1) {
        $prevUrl = get_pagenum_link($paged - 1);
        echo '<a href="' . esc_url($prevUrl) . '">' . $prevIconHtml . '</a>';
      }

      // Page number links
      for ($i = 1; $i <= $numberOfPages; $i++) {
        $pageUrl = get_pagenum_link($i);

        if ($i == $paged) {
          // Current page - highlighted
          echo '<span class="flex items-center h-full px-2 text-white bg-black text-xl font-bold no-underline md:px-3">';
          echo $i;
          echo '</span>';
        } else {
          // Other pages - normal styling
          echo '<a href="' . esc_url($pageUrl) . '" class="flex items-center h-full px-2 text-black bg-white text-xl no-underline md:px-3 hover:bg-gray-100">';
          echo $i;
          echo '</a>';
        }
      }

      // Next page link
      if ($paged < $numberOfPages) {
        $nextUrl = get_pagenum_link($paged + 1);
        echo '<a href="' . esc_url($nextUrl) . '">' . $nextIconHtml . '</a>';
      }
    @endphp
  </nav>
</div>
