module Components
  module Button
    class ButtonComponent < Middleman::Extension
      helpers do
        def button(opts)
          return link_to(opts[:text], opts[:href], build_button_html(opts)) if opts[:link]
          content_tag(:button, opts[:text], build_button_html(opts))
        end
      
        private
        
        def build_button_html(opts)
          additional_classes = opts.dig(:html, :class) ? " #{opts[:html][:class]}" : ""
          combined_classes = "p-4 h-auto font-semibold transition-all border-green-default border-2 border-solid #{button_type(opts[:type])}#{block?(opts[:block])}#{additional_classes}"
          opts[:html] ||= {}
          opts[:html][:class] = combined_classes
          opts[:html]
        end
      
        def button_type(type)
          case type
          when :default
            "bg-transparent text-green-default hover:text-green-darker hover:border-green-darker"
          when :primary
            "bg-green-default text-white hover:bg-green-darker hover:border-green-darker"
          end
        end
      
        def block?(btn_block)
          btn_block == true ? " block w-full" : ""
        end
      end
    end
  end
end

::Middleman::Extensions.register(:button_component, Components::Button::ButtonComponent)
