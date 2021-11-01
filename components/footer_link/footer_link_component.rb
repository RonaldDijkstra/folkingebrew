module Components
  module FooterLink
    class FooterLinkComponent < Middleman::Extension
      helpers do 
        def footer_link(opts)
          additional_classes = opts.dig(:html, :class) ? "#{opts[:html][:class]}" : ""
          link_to opts[:text], 
                      "#{opts[:link]}", 
                      class: "block text-white hover:text-gray-300 mr-3 text-base font-medium #{additional_classes}"
        end
      end
    end
  end
end

::Middleman::Extensions.register(:footer_link_component, Components::FooterLink::FooterLinkComponent)