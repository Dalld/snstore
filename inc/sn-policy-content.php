<?php
/**
 * Default page content created on theme activation.
 * Brand/domain/contact details are tokens replaced at render time —
 * change them once in Customizer ("Site Info") and every page updates.
 *
 * @package snstore
 */

defined( 'ABSPATH' ) || exit;

/**
 * The default pages shipped with the theme.
 *
 * @return array slug => array( title, eyebrow, template, content )
 */
function sn_default_pages() {
	static $pages = null;
	if ( null !== $pages ) {
		return $pages;
	}

	$pages = array(

		'shipping-policy' => array(
			'title'    => 'Shipping Policy',
			'eyebrow'  => 'Customer service',
			'template' => 'policy',
			'content'  => <<<HTML
<p>This Shipping Policy explains how {{brand}} processes and delivers orders placed through {{site_url}}. Please review it before completing your purchase. Delivery dates are estimates rather than guarantees unless we expressly agree otherwise in writing.</p>

<h2>Order Processing</h2>
<p>Orders are normally processed within 1&ndash;3 business days after payment is authorized. Orders placed after 2:00 PM in the store&rsquo;s local time zone, on weekends, or on public holidays begin processing on the next business day. Processing time is separate from carrier transit time.</p>
<p>During product launches, holidays, severe weather, or periods of unusually high demand, processing may take longer. If a material delay affects your order, we will use the contact information provided at checkout to notify you.</p>

<h2>Shipping Destinations</h2>
<p>We currently ship to the destinations made available at checkout. Available destinations and delivery methods are displayed at checkout. We may be unable to deliver to certain restricted locations, freight-forwarding addresses, hotels, military addresses, or post office boxes. If a destination cannot be served, we may cancel the affected order and refund the amount paid.</p>

<h2>Shipping Rates and Free Shipping</h2>
<p>Shipping charges are calculated at checkout based on the destination, package size, weight, order value, and selected service. Any free-shipping offer applies only when the eligibility requirements shown on the website are met. Unless stated otherwise, free shipping uses our standard delivery service and does not include duties, taxes, remote-area fees, or optional expedited services.</p>

<h2>Estimated Delivery Times</h2>
<ul>
<li>Standard domestic delivery: 3&ndash;7 business days after dispatch.</li>
<li>Expedited domestic delivery: 1&ndash;3 business days after dispatch.</li>
<li>International delivery: 7&ndash;21 business days after dispatch.</li>
</ul>
<p>Delivery estimates begin when the carrier accepts the shipment, not when the order is placed. Customs review, weather, local carrier capacity, remote locations, security checks, and events outside our reasonable control may extend these estimates.</p>

<h2>Order Confirmation and Tracking</h2>
<p>After placing an order, you should receive an order confirmation. A shipping confirmation containing tracking information will be sent when tracking is available. Tracking events can take up to 48 hours to appear after a label is created. A shipping label does not necessarily mean the carrier has already collected the parcel.</p>

<h2>Address Accuracy and Changes</h2>
<p>You are responsible for providing a complete and accurate delivery address, including apartment, suite, unit, postal code, and access details. Contact us as soon as possible if an address needs to be corrected. We cannot guarantee changes after an order enters processing or is transferred to the carrier.</p>
<p>Additional postage, carrier correction charges, reshipment costs, or losses caused by an incorrect or incomplete address may be charged to the customer to the extent permitted by law.</p>

<h2>Split Shipments</h2>
<p>Items in the same order may ship separately because of inventory location, package size, product availability, or carrier requirements. When this happens, you may receive more than one tracking number. You will not be charged additional standard shipping solely because we split an order.</p>

<h2>International Orders, Customs, and Taxes</h2>
<p>International shipments may be subject to import duties, taxes, brokerage charges, or customs fees imposed by the destination country. Unless checkout expressly states that duties and taxes are included, the recipient is responsible for these charges. We do not control customs assessments and cannot predict their amount.</p>
<p>You are responsible for confirming that ordered products may lawfully be imported into the destination. Customs delays do not normally qualify an order for cancellation after dispatch. If a shipment is refused or abandoned because duties are unpaid, any refund may be reduced by the original shipping charge, return shipping, duties, storage, and carrier fees, subject to applicable law.</p>

<h2>Delayed Shipments</h2>
<p>Carrier scans and estimated delivery dates are supplied by third parties. If tracking has not updated for several business days or the estimated delivery date has passed, contact the carrier first and then contact us if additional assistance is required. We will make reasonable efforts to investigate, but carrier investigations may take time.</p>

<h2>Lost, Stolen, Damaged, or Missing Packages</h2>
<p>Inspect your delivery promptly. Report a package that is lost in transit, delivered but not received, visibly damaged, or missing items within 7 days after delivery. Keep the product, packaging, shipping label, photographs, and any carrier documentation while the claim is reviewed.</p>
<p>We may ask you to confirm the delivery address, check with household members or neighbors, contact the carrier, or complete a written statement. Available remedies may include replacement, refund, store credit, or carrier claim assistance depending on the circumstances and applicable law. We are not responsible for theft after confirmed delivery where responsibility has legally passed to the recipient, but we will reasonably assist with available carrier information.</p>

<h2>Undeliverable and Refused Shipments</h2>
<p>If a shipment is returned because it was refused, unclaimed, inaccessible, or addressed incorrectly, we will contact you when it is received. Where permitted by law, reshipping charges and non-recoverable carrier fees may be deducted from a refund or charged before redelivery.</p>

<h2>Preorders and Backorders</h2>
<p>Products identified as preorder or backorder items have an estimated availability date on the product page or order confirmation. These dates may change. If an order contains both available and delayed items, we may hold the order or ship it in parts. We will communicate material changes when reasonably possible.</p>

<h2>Risk of Loss</h2>
<p>Risk of loss and title pass according to applicable law and the shipping terms stated at checkout. Nothing in this policy limits consumer rights that cannot legally be waived.</p>

<h2>Policy Changes</h2>
<p>We may update this Shipping Policy when our carriers, destinations, services, or legal obligations change. The version displayed when you place an order generally applies to that order unless a change is required by law.</p>
HTML,
		),

		'return-refund-policy' => array(
			'title'    => 'Return & Refund Policy',
			'eyebrow'  => 'Customer service',
			'template' => 'policy',
			'content'  => <<<HTML
<p>We want you to be confident in your purchase from {{brand}}. This Refund and Returns Policy explains when products may be returned, how to request a return, and how refunds and exchanges are handled. Nothing in this policy limits rights that cannot be excluded under applicable consumer law.</p>

<h2>Return Window</h2>
<p>Unless a different period is stated on the product page, you may request an eligible return within 30 days after the order is delivered. A return request must be submitted within that period, and the approved return must be shipped within 14 days after authorization.</p>

<h2>Return Eligibility</h2>
<p>To qualify for a return, an item must generally be:</p>
<ul>
<li>unused, unworn, unwashed, and unaltered;</li>
<li>in resalable condition with original tags, accessories, manuals, and packaging;</li>
<li>accompanied by valid proof of purchase; and</li>
<li>returned in accordance with the instructions and authorization we provide.</li>
</ul>
<p>Reasonable inspection needed to evaluate a product does not affect statutory rights. We may reduce or refuse a discretionary refund if handling goes beyond what is reasonably necessary and causes diminished value, where the law permits.</p>

<h2>Items That Cannot Be Returned</h2>
<p>Unless defective or otherwise required by law, the following are not eligible for return:</p>
<ul>
<li>products marked Final Sale, clearance, or non-returnable before purchase;</li>
<li>gift cards, downloadable products, and activated digital goods;</li>
<li>personalized, engraved, custom-made, or made-to-order products;</li>
<li>perishable goods, plants, food, and time-sensitive products;</li>
<li>personal-care, hygiene, intimate, cosmetic, or health products after their seal is opened;</li>
<li>hazardous materials, flammable liquids, gases, and items subject to shipping restrictions;</li>
<li>products damaged through misuse, accident, improper installation, unauthorized repair, or normal wear; and</li>
<li>products purchased from another retailer or marketplace.</li>
</ul>

<h2>Starting a Return</h2>
<p>Contact us before sending anything back. Include your order number, the item you want to return, the reason for return, and photographs when the product is damaged, defective, or incorrect. If approved, we will provide return instructions and, when applicable, a return authorization or shipping label.</p>
<p>Items returned without authorization, sent to an incorrect address, shipped cash-on-delivery, or delivered after the approved period may be refused. Do not send returns to the manufacturer unless we specifically instruct you to do so.</p>

<h2>Return Shipping</h2>
<p>For a change-of-mind return, the customer is responsible for return shipping unless the product page, promotion, or applicable law states otherwise. Original shipping and optional expedited charges are non-refundable except where required by law or where the return results from our error.</p>
<p>We pay reasonable return shipping for verified defective, damaged, or incorrect items. Use a trackable service and retain the receipt. We are not responsible for a customer-arranged return lost before it reaches the designated return location.</p>

<h2>Damaged, Defective, or Incorrect Items</h2>
<p>Inspect your order promptly and report damage, defects, missing parts, or an incorrect item within 7 days after delivery. Provide clear photographs of the item, packaging, shipping label, and damage when requested. Do not discard packaging or attempt repairs until we provide instructions.</p>
<p>After review, we may repair or replace the item, provide missing parts, issue store credit, or refund the eligible amount. The available remedy depends on the circumstances, inventory, product warranty, and applicable law.</p>

<h2>Exchanges</h2>
<p>Exchange availability depends on inventory. The fastest method may be to return the original item and place a new order after the return is approved. Price differences, taxes, and shipping charges may apply. We do not guarantee that a requested replacement will remain available while a return is in transit.</p>

<h2>Return Inspection</h2>
<p>We will notify you after the return is received and inspected. Inspection normally takes 3&ndash;5 business days. If the return does not meet this policy, we will explain the reason and may offer to send the item back at your expense, where permitted by law.</p>

<h2>Refunds</h2>
<p>Approved refunds are issued to the original payment method unless store credit or another method is agreed or required by law. Refunds normally include the eligible product price and applicable taxes. Shipping charges, duties, and fees are handled as described in this policy and applicable law.</p>
<p>We generally initiate approved refunds within 5&ndash;10 business days after inspection. Banks, card issuers, and payment providers may require additional time to post the credit. We cannot control their processing times.</p>

<h2>Late or Missing Refunds</h2>
<p>If you have not received an expected refund, check the original payment account and contact your bank or card issuer. If their processing period has passed, contact us with the order number and refund confirmation so we can investigate.</p>

<h2>Order Changes and Cancellations</h2>
<p>Contact us as soon as possible to request a change or cancellation. We cannot guarantee cancellation after processing begins. Once an order has shipped, it must follow the return process. Custom, personalized, digital, and final-sale orders may not be cancellable after production or fulfillment starts.</p>

<h2>Promotional Purchases, Bundles, and Gifts</h2>
<p>Returns from bundles or multi-buy promotions may require all qualifying items to be returned, or the refund may be adjusted to reflect the price of items kept. Free gifts must be returned when the qualifying purchase is returned unless we state otherwise. Gift recipients may be offered store credit when the original payment method belongs to the purchaser.</p>

<h2>International Returns</h2>
<p>International customers should contact us before returning an item. The customer is generally responsible for international return postage, customs documents, duties, and taxes unless the return results from our verified error or applicable law requires otherwise. Marking a return accurately is the sender&rsquo;s responsibility.</p>

<h2>Abuse and Fraud Prevention</h2>
<p>We may refuse transactions or limit discretionary returns where we reasonably identify fraud, return abuse, wardrobing, altered products, false claims, or repeated policy violations. We will not apply this provision in a way that unlawfully restricts statutory consumer rights.</p>

<h2>Consumer Rights</h2>
<p>Customers in some jurisdictions have mandatory cancellation, withdrawal, repair, replacement, or refund rights. Those rights apply in addition to this policy and take priority where they provide greater protection.</p>

<h2>Policy Changes</h2>
<p>We may update this policy to reflect changes in our products, operations, or legal obligations. The policy applicable when the order was placed will generally govern that purchase unless otherwise required by law.</p>
HTML,
		),

		'warranty-policy' => array(
			'title'    => 'Warranty Policy',
			'eyebrow'  => 'Customer service',
			'template' => 'policy',
			'content'  => <<<HTML
<p>Last updated: September 8, 2026</p>

<h2>90-Day Product Warranty</h2>
<p>We stand behind the products we sell. If an item has a manufacturing defect within 90 days of delivery, we will offer a replacement or refund after reviewing the claim.</p>

<h2>What Is Covered</h2>
<p>Coverage applies to defects in materials or workmanship that were present when the product was manufactured, including structural failures, faulty components, or finishes that fail under normal intended use.</p>

<h2>What Is Not Covered</h2>
<ul>
<li>Normal wear and tear or cosmetic changes from regular use.</li>
<li>Accidental damage, misuse, improper storage, or failure to follow care instructions.</li>
<li>Damage caused by unauthorized modification or attempted repair.</li>
<li>Lost parts, accessories, or damage occurring after the 90-day period.</li>
</ul>

<h2>How to Make a Claim</h2>
<p>Contact us with your order number, a short description of the issue, and clear photos or video showing the defect. We normally review claims within 1&ndash;2 business days.</p>

<h2>Resolution</h2>
<p>Approved claims may receive the same replacement item, a comparable replacement when the original is unavailable, or a refund to the original payment method. If inspection is required, we will provide return instructions.</p>

<h2>Questions?</h2>
<p>Contact our team if you need help understanding this information.</p>
HTML,
		),

		'privacy-policy' => array(
			'title'    => 'Privacy Policy',
			'eyebrow'  => 'Legal',
			'template' => 'policy',
			'content'  => <<<HTML
<p>Last updated: September 8, 2026</p>

<p>This Privacy Policy explains how {{brand}} collects, uses, discloses, and protects personal information when you visit {{site_url}}, create an account, make a purchase, contact us, subscribe to communications, or otherwise interact with our services.</p>
<p>In this policy, &ldquo;personal information&rdquo; means information that identifies, relates to, describes, or can reasonably be linked with an individual or household. It does not include information that is lawfully considered anonymous or de-identified.</p>

<h2>Who Is Responsible for Your Information</h2>
<p>{{brand}} is responsible for the personal information described in this policy unless another entity is identified at the point of collection. In some jurisdictions, this means we act as the data controller or business.</p>

<h2>Information We Collect</h2>
<p>Depending on how you interact with us, we may collect:</p>
<ul>
<li><strong>Contact information:</strong> name, email address, telephone number, billing address, shipping address, and account username.</li>
<li><strong>Order and transaction information:</strong> products viewed or purchased, order numbers, transaction status, discounts, returns, refunds, delivery details, and customer-service history.</li>
<li><strong>Payment information:</strong> payment type, billing details, and limited transaction identifiers. Complete card or banking credentials are generally handled by payment providers rather than stored by us.</li>
<li><strong>Device and usage information:</strong> IP address, browser, device type, operating system, referring URLs, pages viewed, clicks, approximate location derived from IP, session information, and diagnostic logs.</li>
<li><strong>Preferences and communications:</strong> marketing choices, product interests, survey responses, reviews, messages, photographs, and other content you submit.</li>
<li><strong>Fraud and security information:</strong> account activity, device signals, transaction risk indicators, and information used to protect our customers and business.</li>
<li><strong>Information required by law:</strong> tax, customs, identity, age, sanctions-screening, or compliance information when legally necessary.</li>
</ul>

<h2>How We Collect Information</h2>
<p>We collect information directly from you, automatically through cookies and similar technologies, and from third parties such as payment processors, ecommerce and hosting providers, analytics providers, advertising partners, delivery carriers, fraud-prevention providers, social platforms, and publicly available sources.</p>

<h2>How We Use Personal Information</h2>
<p>We may use personal information to:</p>
<ul>
<li>provide the website, create accounts, process orders, collect payment, arrange delivery, and handle returns;</li>
<li>authenticate users, prevent fraud, protect transactions, and maintain security;</li>
<li>communicate about orders, accounts, support requests, recalls, and policy changes;</li>
<li>personalize products, recommendations, content, and customer experience;</li>
<li>send marketing when permitted and manage communication preferences;</li>
<li>measure website performance, understand customer behavior, and improve products and services;</li>
<li>administer promotions, loyalty programs, surveys, and reviews;</li>
<li>maintain business records, enforce agreements, resolve disputes, and protect legal rights; and</li>
<li>comply with tax, accounting, consumer-protection, sanctions, law-enforcement, and other legal obligations.</li>
</ul>

<h2>Legal Bases for Processing</h2>
<p>Where laws such as the UK or European Economic Area data-protection rules apply, we process information when necessary to perform a contract with you, comply with legal obligations, pursue legitimate interests that are not overridden by your rights, protect vital interests, or act with your consent. Legitimate interests may include operating and securing the store, preventing fraud, improving services, and conducting proportionate marketing and analytics.</p>

<h2>Cookies and Similar Technologies</h2>
<p>We and our providers may use cookies, pixels, local storage, software development kits, and similar technologies. These technologies can keep the cart working, remember preferences, maintain security, measure traffic, analyze performance, and support advertising.</p>
<p>You can control cookies through our consent tool, where available, and through browser settings. Blocking essential cookies may prevent checkout, account, or cart functions from working. Browser controls do not necessarily affect technologies used by mobile apps or other devices.</p>

<h2>Marketing Communications</h2>
<p>Where permitted, we may send email, text, or other marketing based on your consent or our legitimate interests. You may unsubscribe using the link in a marketing message or contact us. You may still receive non-marketing messages concerning orders, accounts, security, or legal notices.</p>

<h2>How We Disclose Personal Information</h2>
<p>We may disclose information to:</p>
<ul>
<li>ecommerce, website-hosting, cloud-storage, security, and customer-support providers;</li>
<li>payment processors, banks, fraud-prevention services, and transaction partners;</li>
<li>warehouses, suppliers, manufacturers, delivery carriers, customs brokers, and return processors;</li>
<li>analytics, advertising, marketing, review, and communication providers;</li>
<li>professional advisers, insurers, auditors, and accountants;</li>
<li>government authorities, regulators, courts, or other parties when disclosure is legally required or reasonably necessary to protect rights and safety; and</li>
<li>a buyer, investor, lender, or successor in connection with a merger, financing, restructuring, sale, or transfer of all or part of the business.</li>
</ul>
<p>Providers are expected to use information only for authorized purposes and protect it appropriately, subject to their legal roles and agreements.</p>

<h2>Targeted Advertising, Sale, and Sharing</h2>
<p>We do not exchange personal information for money unless we clearly disclose otherwise. Some privacy laws may define the use of advertising cookies or disclosure of identifiers to advertising partners as a &ldquo;sale,&rdquo; &ldquo;sharing,&rdquo; or targeted advertising even when no money changes hands.</p>
<p>Where required, you may opt out through the &ldquo;Your Privacy Choices&rdquo; or &ldquo;Do Not Sell or Share My Personal Information&rdquo; link, cookie settings, or the contact method below. We will also process legally recognized browser-based opt-out signals, such as Global Privacy Control, where required.</p>

<h2>Payment Processing</h2>
<p>Payment providers process payment information under their own privacy policies and security standards. We may receive limited details such as payment method, authorization status, billing name, and transaction identifier. Review the privacy notice of the payment provider presented at checkout for more information.</p>

<h2>Data Retention</h2>
<p>We retain personal information only as long as reasonably necessary for the purposes described in this policy, including completing transactions, maintaining accounts, supporting customers, meeting tax and accounting requirements, preventing fraud, resolving disputes, and enforcing agreements.</p>
<p>Retention periods vary by information type, legal requirements, contractual obligations, sensitivity, and risk. We delete or de-identify information when it is no longer needed, unless continued retention is permitted or required by law.</p>

<h2>Security</h2>
<p>We use reasonable administrative, technical, and physical safeguards designed for the nature of the information we handle. No website, storage system, or transmission method can be guaranteed completely secure. Protect your password, use unique credentials, and notify us if you suspect unauthorized account activity.</p>

<h2>Your Privacy Rights</h2>
<p>Depending on where you live and subject to legal exceptions, you may have rights to:</p>
<ul>
<li>know whether we process your personal information and obtain access to it;</li>
<li>request correction of inaccurate information;</li>
<li>request deletion of certain information;</li>
<li>receive a portable copy of certain information;</li>
<li>restrict or object to certain processing;</li>
<li>withdraw consent without affecting earlier lawful processing;</li>
<li>opt out of targeted advertising, sale, or sharing as those terms are legally defined;</li>
<li>appeal our response where applicable; and</li>
<li>receive equal service and pricing without unlawful discrimination for exercising privacy rights.</li>
</ul>
<p>To submit a request, use the privacy contact information shown on this page. We may verify your identity and authority before acting. Authorized agents may submit requests when permitted, but we may require proof of authorization. We will respond within the period required by applicable law.</p>

<h2>Regional Disclosures</h2>
<p><strong>United States:</strong> Residents of certain states may have rights regarding access, correction, deletion, portability, targeted advertising, sale, sharing, sensitive information, and appeals. We provide applicable choices as described above.</p>
<p><strong>European Economic Area, United Kingdom, and Switzerland:</strong> You may contact your local data-protection authority if you believe your rights have been violated. We encourage you to contact us first so we can try to address the concern.</p>
<p><strong>Other regions:</strong> Additional local rights may apply. We will honor valid requests as required by the law applicable to our activities.</p>

<h2>International Data Transfers</h2>
<p>We and our providers may process information in countries other than where you live. Those countries may have different data-protection laws. Where required, we use recognized safeguards such as adequacy decisions, contractual protections, or other lawful transfer mechanisms.</p>

<h2>Children&rsquo;s Privacy</h2>
<p>Our services are not directed to children under 13, and we do not knowingly collect personal information from children below the age requiring parental consent. If you believe a child has provided personal information improperly, contact us so we can investigate and delete it where required.</p>

<h2>Third-Party Websites</h2>
<p>Links to third-party websites, apps, or services are provided for convenience. Their privacy practices are governed by their own notices, and we are not responsible for services we do not control.</p>

<h2>Changes to This Policy</h2>
<p>We may update this Privacy Policy to reflect operational, technological, or legal changes. The revised policy will be posted with an updated date. Where required, we will provide additional notice or obtain consent before materially different uses of personal information.</p>

<h2>Contact for Privacy Inquiries</h2>
<p>To exercise your privacy rights or ask a question about this policy, contact us using the details below.</p>
HTML,
		),

		'terms-and-conditions' => array(
			'title'    => 'Terms and Conditions',
			'eyebrow'  => 'Legal',
			'template' => 'policy',
			'content'  => <<<HTML
<p>Last updated: September 8, 2026</p>

<p>These Terms and Conditions govern your access to and use of {{site_url}} and your purchase of products from {{brand}}. By visiting the website, creating an account, submitting an order, or otherwise using our services, you agree to these Terms and our Privacy Policy, Shipping Policy, and Refund and Returns Policy.</p>
<p>If you do not agree, do not use the website or place an order. Certain legal rights vary by location, and nothing in these Terms excludes rights that cannot lawfully be excluded.</p>

<h2>1. Eligibility</h2>
<p>You must be at least 18 years old, or the age of legal majority where you live, to make a purchase. If you allow a minor to use a device or account under your control, you are responsible for that use to the extent permitted by law.</p>

<h2>2. Changes to These Terms</h2>
<p>We may update these Terms to reflect changes in our services, business practices, or legal obligations. The updated version becomes effective when posted unless a later date is stated. Changes do not retroactively alter an order already accepted except where required by law or agreed with you.</p>

<h2>3. Accounts and Account Security</h2>
<p>You may need an account to use certain features. You agree to provide accurate, current information and keep it updated. You are responsible for safeguarding your credentials and for activity conducted through your account, except to the extent caused by our failure to use reasonable security.</p>
<p>Notify us promptly if you suspect unauthorized access. We may suspend or restrict an account when reasonably necessary to protect customers, investigate fraud, enforce these Terms, or comply with law.</p>

<h2>4. Product Information</h2>
<p>We aim to describe products accurately, but colors, scale, texture, packaging, measurements, and appearance may vary because of display settings, manufacturing changes, photography, or natural product variation. Images are illustrative unless expressly stated otherwise.</p>
<p>Product availability is not guaranteed. We may correct descriptions, specifications, or availability information before accepting an order. Material changes affecting an accepted order will be communicated when reasonably possible.</p>

<h2>5. Prices, Taxes, and Charges</h2>
<p>Prices are displayed in USD unless otherwise stated. Applicable taxes, shipping charges, duties, and fees are calculated or disclosed at checkout where reasonably possible. International customers may owe additional import charges imposed by local authorities.</p>
<p>If a price or promotion is clearly erroneous, we may cancel the affected order before fulfillment and refund the amount paid. We will not charge a corrected higher price without your agreement.</p>

<h2>6. Orders and Contract Formation</h2>
<p>An order submission is an offer to purchase. An automated confirmation acknowledges receipt but does not necessarily mean the order has been accepted. Acceptance occurs when we dispatch the product or otherwise expressly confirm acceptance, subject to applicable law.</p>
<p>We may reject or cancel an order for legitimate reasons, including inventory errors, payment failure, suspected fraud, unlawful activity, shipping restrictions, obvious pricing errors, or quantity limits. If we cancel after payment, we will refund the cancelled amount.</p>

<h2>7. Payment</h2>
<p>You represent that you are authorized to use the selected payment method and authorize the applicable charges. Payments may be processed by independent payment providers under their own terms and privacy notices. We do not normally receive complete payment-card details.</p>
<p>Payment authorization may occur when the order is placed, while capture may occur immediately or when the order is processed, depending on the method and jurisdiction.</p>

<h2>8. Shipping and Delivery</h2>
<p>Processing times, delivery estimates, tracking, customs, address responsibilities, and lost or damaged shipment procedures are described in our Shipping Policy. Delivery estimates are not guarantees unless expressly agreed. Risk of loss and title pass according to applicable law and the shipping terms presented at checkout.</p>

<h2>9. Returns, Refunds, and Cancellations</h2>
<p>Returns, refunds, exchanges, damaged-item claims, and cancellation requests are governed by our Refund and Returns Policy. Mandatory consumer rights continue to apply regardless of any shorter discretionary return period.</p>

<h2>10. Promotions, Discount Codes, and Gift Cards</h2>
<p>Promotions may have additional eligibility rules, dates, exclusions, or quantity limits. Unless stated otherwise, offers cannot be combined, transferred, redeemed for cash, or applied retroactively. We may cancel a promotion or refuse misuse, but changes will not unlawfully affect completed purchases.</p>
<p>Gift cards and store credits are governed by the terms disclosed when issued and applicable law. Keep codes secure; we may be unable to replace value used by an unauthorized person when the loss was not caused by us.</p>

<h2>11. Acceptable Use</h2>
<p>You must not:</p>
<ul>
<li>use the website for unlawful, fraudulent, deceptive, or abusive activity;</li>
<li>interfere with website security, availability, servers, or networks;</li>
<li>introduce malware, harmful code, automated attacks, or excessive requests;</li>
<li>scrape, crawl, copy, or harvest content or personal information except as permitted by law or written authorization;</li>
<li>circumvent access restrictions, purchase limits, or technical protections;</li>
<li>impersonate another person or misrepresent affiliation;</li>
<li>use our content, trademarks, or services to create a misleading or competing offering; or</li>
<li>infringe intellectual-property, privacy, publicity, or other rights.</li>
</ul>

<h2>12. Intellectual Property</h2>
<p>The website and its original text, graphics, logos, photographs, design, software, and other content are owned by or licensed to {{brand}} and protected by applicable intellectual-property laws. We grant you a limited, revocable, non-exclusive, non-transferable right to use the website for personal shopping and lawful informational purposes.</p>
<p>No ownership rights are transferred. Third-party names and marks belong to their respective owners.</p>

<h2>13. Reviews and User Content</h2>
<p>If you submit a review, photograph, comment, or other content, you confirm that you have the right to do so and that it is accurate, lawful, and does not violate another person&rsquo;s rights. You retain ownership of your content.</p>
<p>You grant us a worldwide, non-exclusive, royalty-free license to host, reproduce, adapt, publish, display, and distribute that content for operating, improving, and promoting our business, subject to applicable privacy law and any platform-specific terms disclosed when you submit it. We may moderate or remove content that violates these Terms but are not obligated to monitor every submission.</p>

<h2>14. Third-Party Services and Links</h2>
<p>The website may link to or integrate payment processors, carriers, social networks, analytics providers, or other third parties. Their services are governed by their own terms and privacy practices. A link does not imply endorsement, and we are not responsible for third-party services outside our control.</p>

<h2>15. Service Availability</h2>
<p>We may maintain, update, suspend, or discontinue website features. We do not promise uninterrupted or error-free access. We will use reasonable care in operating the service but are not responsible for interruptions caused by maintenance, networks, hosting providers, cyber incidents, force majeure, or events outside our reasonable control.</p>

<h2>16. Disclaimers</h2>
<p>To the maximum extent permitted by law, the website and general informational content are provided on an &ldquo;as available&rdquo; basis. We disclaim implied warranties that may legally be excluded. Product warranties, if any, are stated on the product page or in a separate warranty policy.</p>
<p>Nothing on the website is professional medical, legal, financial, or safety advice. Always follow product instructions, warnings, and applicable regulations.</p>

<h2>17. Limitation of Liability</h2>
<p>To the maximum extent permitted by law, {{brand}} and its officers, employees, and service providers will not be liable for indirect, incidental, special, punitive, or consequential losses, or for lost profits, revenue, data, or opportunity arising from use of the website.</p>
<p>Where liability may lawfully be limited, our aggregate liability relating to a product or order will not exceed the amount paid for the product or order giving rise to the claim. These limitations do not apply to fraud, willful misconduct, death or personal injury caused by negligence, breach of mandatory consumer guarantees, or any liability that cannot legally be limited.</p>

<h2>18. Indemnification</h2>
<p>To the extent permitted by law, you agree to reimburse reasonable losses and expenses resulting from your unlawful use of the website, your material breach of these Terms, or your infringement of another person&rsquo;s rights. This provision does not apply to losses caused by us and does not reduce mandatory consumer protections.</p>

<h2>19. Suspension and Termination</h2>
<p>You may stop using the website at any time. We may suspend or terminate access when reasonably necessary because of a material breach, fraud, security risk, legal requirement, or harm to other users. Provisions that by their nature should survive termination will continue to apply.</p>

<h2>20. Governing Law and Disputes</h2>
<p>These Terms are governed by the laws of the jurisdiction in which our business is registered, without regard to conflict-of-law principles, except that mandatory consumer laws in your place of residence may still apply.</p>
<p>Before filing a formal claim, please contact us and allow a reasonable opportunity to resolve the issue. Any court proceedings will be brought in the courts of the location where our business is registered, unless applicable law gives you the right to bring a claim elsewhere. No arbitration or class-action waiver applies unless separately disclosed and legally agreed.</p>

<h2>21. General Provisions</h2>
<p>If a provision is found unenforceable, it will be modified only as necessary and the remaining provisions will continue in effect. Our failure to enforce a provision is not a waiver. You may not transfer your rights or obligations without our consent; we may transfer ours as part of a merger, sale, reorganization, or transfer of the business, subject to applicable law.</p>
<p>These Terms and the policies incorporated by reference form the agreement concerning your use of the website and purchases, except for additional terms expressly agreed for a particular product or service.</p>
HTML,
		),

		'about-us' => array(
			'title'    => 'About Us',
			'eyebrow'  => 'Our company',
			'template' => 'policy',
			'content'  => <<<HTML
<h2>Why We Started</h2>
<p>We created this store around a simple idea: everyday products should be useful, fairly priced, and easy to buy. Shopping online should not mean navigating unclear policies or waiting endlessly for support.</p>

<h2>What We Choose</h2>
<p>We work with established suppliers and distributors to select practical products that offer strong value. We focus on function, clear product information, and a straightforward assortment rather than unnecessary complexity.</p>

<h2>How We Work</h2>
<p>We operate as an independent online retailer supported by fulfillment partners. This model lets us stay focused on product selection, responsive service, and a smooth experience from checkout through delivery.</p>

<h2>What You Can Expect</h2>
<ul>
<li>Clear pricing and product details.</li>
<li>Transparent shipping, return, warranty, and privacy policies.</li>
<li>Secure checkout through established payment providers.</li>
<li>Helpful customer support from real people.</li>
</ul>

<h2>Always Improving</h2>
<p>We review customer questions, delivery experiences, and product feedback to improve the store over time. If something is unclear or could work better, we want to hear about it.</p>
HTML,
		),

		'contact-us' => array(
			'title'    => 'Contact Us',
			'eyebrow'  => 'Customer service',
			'template' => 'contact',
			'content'  => <<<HTML
<p>Questions about an order or product? Send us a message and our team will get back to you.</p>
HTML,
		),

		'payment-methods' => array(
			'title'    => 'Payment Methods',
			'eyebrow'  => 'Secure checkout',
			'template' => 'policy',
			'content'  => <<<HTML
<p>Last updated: September 8, 2026</p>

<h2>Accepted Payment Methods</h2>
<p>We accept major payment options available at checkout, including:</p>
<ul>
<li>Visa</li>
<li>Mastercard</li>
<li>PayPal</li>
</ul>

<h2>Payment Security</h2>
<p>Checkout information is encrypted in transit using SSL. Card and wallet payments are processed by PCI-compliant payment providers, and complete card details are not stored on our website.</p>

<h2>When You Are Charged</h2>
<p>Your selected payment method is authorized or charged when the order is placed. If an order is canceled before fulfillment, any eligible release or refund is sent to the original payment method.</p>

<h2>Currency</h2>
<p>Prices and transactions are displayed in US Dollars (USD) unless checkout states otherwise. Your bank or payment provider may apply currency conversion or international transaction fees.</p>

<h2>Order Verification</h2>
<p>To protect customers and prevent fraud, we may verify billing details or contact you before processing certain orders. Orders that cannot be verified may be canceled and refunded.</p>

<h2>Questions?</h2>
<p>Contact our team if you need help understanding this information.</p>
HTML,
		),
	);

	return $pages;
}
