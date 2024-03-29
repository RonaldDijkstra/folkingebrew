module Components
  module AccordionButton
    class AccordionButtonComponent < Middleman::Extension
      helpers do
        def accordion_button(opts = nil)
          additional_classes = opts.dig(:html, :class) ? (opts[:html][:class]).to_s : ''
          link_to link_text(opts[:text]),
                  'javascript:void(0);',
                  class: "accordion-button flex justify-between w-full
                          border-b border-black bg-white font-bold py-3
                          #{additional_classes}"
        end

        def link_text(text)
          "#{text}
          <svg width=\"24\" height=\"24\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"
          class=\"accordion-icon transform transition-transform duration-500 ease-in-out
                  motion-reduce:transition-none motion-reduce:transform-none\">
            <path stroke-linecap=\"round\" stroke-linejoin=\"round\"
            stroke-width=\"2\" d=\"M19 9l-7 7-7-7\"></path>
          </svg>"
        end
      end
    end
  end
end

::Middleman::Extensions.register(:accordion_button_component,
                                 Components::AccordionButton::AccordionButtonComponent)
