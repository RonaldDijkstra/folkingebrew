module Components
  module Button
    class ButtonComponent < Middleman::Extension
      helpers do
        def button(opts)
          return link_to(opts[:text], opts[:href], build_button_html(opts)) if opts[:link]

          content_tag(:button, opts[:text], build_button_html(opts))
        end

        def build_button_html(opts)
          additional_classes = opts.dig(:html, :class) ? " #{opts[:html][:class]}" : ''
          combined_classes = "p-4 h-auto font-semibold transition-all border-2 border-solid
                              #{button_type(opts[:type].to_s)}#{additional_classes}
                              motion-reduce:transition-none motion-reduce:transform-none"
          opts[:html] ||= {}
          opts[:html][:class] = combined_classes
          opts[:html]
        end

        def button_type(type)
          lists = { 'default' => 'bg-transparent text-green-default border-green-default
                                  hover:text-green-darker hover:border-green-darker',
                    'default_white' => 'bg-transparent text-white border-white
                                        hover:text-gray-100 hover:border-gray-100',
                    'default_gray' => 'bg-gray-400 border-gray-400 text-white
                                       hover:bg-gray-500 hover:border-gray-500',
                    'primary' => 'bg-green-default border-green-default text-white
                                  hover:bg-green-darker hover:border-green-darker' }

          lists[type]
        end
      end
    end
  end
end

::Middleman::Extensions.register(:button_component, Components::Button::ButtonComponent)
