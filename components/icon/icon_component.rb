module Components
  module Icon
    class IconComponent < Middleman::Extension
      helpers do
        def icon(opts, &block)
          additional_classes = opts.dig(:html, :class) ? " #{opts[:html][:class]}" : ''
          link_to(opts[:href],
                  class: "transition-all block text-white hover:text-gray-300 #{additional_classes}
                          motion-reduce:transition-none motion-reduce:transform-none",
                  target: '_blank', rel: 'noopener noreferrer', &block)
        end
      end
    end
  end
end

::Middleman::Extensions.register(:icon_component, Components::Icon::IconComponent)
