module Components
  module RegularContent
    class RegularContentComponent < Middleman::Extension
      helpers do 
        def regular_content(opts = nil, &block)
          if opts
            additional_classes = opts.dig(:html, :class) ? " #{opts[:html][:class]}" : ""
            content_tag(:section, nil, class: "max-w-4xl py-12 px-6 sm:px-32 bg-white m-auto text-lg #{additional_classes}", &block)
          else 
            content_tag(:section, nil, class: "max-w-4xl py-12 px-6 sm:px-32 bg-white m-auto text-lg", &block)
          end 
        end
      end
    end
  end
end

::Middleman::Extensions.register(:regular_content_component, Components::RegularContent::RegularContentComponent)
