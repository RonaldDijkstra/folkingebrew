module Components
  module AccordionContent
    class AccordionContentComponent < Middleman::Extension
      helpers do
        def accordion_content(&block)
          content_tag(:div, class: "accordion-content hidden prose pt-2", &block)
        end
      end
    end
  end
end

::Middleman::Extensions.register(:accordion_content_component,
                                 Components::AccordionContent::AccordionContentComponent)
