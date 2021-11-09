module Components
  module Grid
    class GridComponent < Middleman::Extension
      helpers do
        def grid(opts, &block)
          additional_classes = opts.dig(:html, :class) ? " #{opts[:html][:class]}" : ""
          classes = "grid md:grid-cols-2 lg:grid-cols-3 gap-9 mb-10 max-w-7xl m-auto px-6 #{additional_classes}"
          return content_tag(opts[:content_tag], class: classes, &block) if opts[:content_tag]
          content_tag(:section, class: classes, &block)
        end
      end
    end
  end
end

::Middleman::Extensions.register(:grid_component, Components::Grid::GridComponent)
