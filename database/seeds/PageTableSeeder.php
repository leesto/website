<?php

use App\Page;
use Illuminate\Database\Seeder;

class PageTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 * @return void
	 */
	public function run()
	{
		// su2bc account
		$user = \App\User::where('username', 'su2bc')->first();

		// Home page
		Page::create([
			'title'     => 'Home',
			'slug'      => 'home',
			'content'   => <<<EOF
<p style="text-align: center;"><img title="BUSMS''s performance of Grease, 2015" src="/images/grease2015.jpg" alt="" /></p>
<p>Backstage Technical Services is a society formed of students at the University of Bath, who provide technical expertise to other Students' Union Clubs and Societies and event organisers in Sound, Lighting and Stage Management.</p>
<p>We offer support at a wide range of events including the Students' Union's Summer Ball and Freshers' Week, theatre performances from student and external performers, band and club nights in The Tub and many more.</p>
<p>We work both on- and off-campus providing lighting, sound, set design, pyrotechnic, stage management and event management expertise.</p>
<p>Whether you're interested in booking us to support your event, or are a student at the University of Bath interested in joining us, please explore the site and do not hesitate to <a href="/contact/enquiries">contact us</a> if you have any questions.</p>
EOF
,
			'published' => true,
			'user_id'   => $user->id,
		]);

		// About
		Page::create([
			'title'     => 'About Us',
			'slug'      => 'about',
			'content'   => <<<EOF
<h2>What are we about?</h2>
<p>Backstage Technical Services is a group of overworked, under-fed and (if it's possible to say this) under-slept students who provide technical expertise to other Students' Union Clubs and Societies and event organisers in Sound, Lighting and Stage Management.</p>
<p>The fact that we exist solely to assist other societies and members of the Union and University to put on events makes Backstage a unique group within the Students' Union, although we operate according to society rules and are just as much a club/society as any other.</p>
<h2>What types of event do we provide services for?</h2>
<p>All of the big events (and most of the smaller events) are crewed by members of Backstage - all of the bands that appear in the Tub, the experience that is Freshers' Week, The Summer Ball, plays by Bath University Student Theatre (BUST), musicals by Bath University Student Musical Society (BUSMS) and many, many more.</p>
<p>We work both on- and off-campus providing lighting, sound, set design, pyrotechnic, stage management and event management expertise.</p>
<h2>Training</h2>
<p>Backstage run a training course which provides continuous training from basic to advanced level on a wide range of subjects. Our training is open (and completely free) to all BTS members. Sessions this year have included stage management, sound engineering, lighting design and pyrotechnics.</p>
<h2>Booking</h2>
<p>You can submit a booking for Backstage using the <a href="/contact/book">online booking form</a>.</p>
<h2>FAQ (Frequently Asked Questions)</h2>
<h3>How can I book Backstage?</h3>
<p>Only via the web. It doesn't matter if you've spoken to every person in the society; we only accept bookings via our web booking system. Booking BTS as early as possible gives us more opportunity to ensure that the correct equipment, volunteers and planning is available. This generally means that we are booked around a month in advance (less than two weeks notice may result in a financial surcharge).</p>
<h3>Do BTS members get paid?</h3>
<p>No. All our members are volunteers, doing full-time degrees here at the university. They sometimes work over 15 hours/day for an event, unpaid. To give an indication of cost, an experienced technician from an external company would cost over &pound;100/day.</p>
<h3>Why can't you do this/that event for free?</h3>
<p>Because we need to pay for insurance, repairs, replacements, transport, photocopying costs, faxes, telephones, tape, bulbs, batteries, smoke fluid etc etc. Every year these costs add upto about &pound;10,000 (not including hires) and we therefore need to issue a charge to break even at the end of each year.</p>
<p>Whilst BTS do receive a budget from the union, it can't cover all our costs, especially for the upkeep of equipment that may be used over one hundred times a year.</p>
<h3>Backstage is very expensive! I can get it cheaper elsewhere</h3>
<p>To hire the equipment externally it would normally cost well over &pound;250 per event plus personnel costs.</p>
<p>People hiring are only charged fees that relate to hire of equipment, insurance and upkeep. All of the supervision is given free through volunteers' time. For example, a full band night in a venue would cost a society around &pound;350: the current Backstage charge is less than half of that (assuming all kit required is available internally).</p>
<p>You are quite welcome to use an external company; we would recommend <a href="http://www.enlightenedlighting.co.uk/" target="_blank">Enlightened Lighting</a> in Bath, and <a href="http://www.audioforum.co.uk/" target="_blank">Audio Forum</a> in Bristol.</p>
<h3>The equipment is BUSU property, I'm a BUSU member, can I borrow it?</h3>
<p>The majority of our equipment requires experience (and usually training) before it can be used and therefore it is rare for Backstage to run dry hires (providing equipment without personnel). For insurance and safety reasons, BTS doesn't lend out equipment to private individuals. Societies have borrowed some of our equipment in the past but each request is considered on an individual basis.</p>
<h3>I want to join Backstage!</h3>
<p>Great idea - we're open to all members of Bath University Students' Union. Our weekly meetings happen on Wednesdays at 13:15 and membership only costs &pound;4. For further details, please contact our Secretary at <a href="mailto:sec@bts-crew.com">sec@bts-crew.com</a>.</p>
EOF
,
			'published' => true,
			'user_id'   => $user->id,
		]);

		// Links
		Page::create([
			'title'     => 'Links',
			'slug'      => 'links',
			'content'   => <<<EOF
<p>This page contains links to many other web resources, divided by category. Please note that these sites are not associated with BTS unless otherwise stated. If you would like a link to be added then please <a href="/contact/enquiries">contact us</a>.</p>
<h3>Lighting Manufacturers</h3>
<ul>
<li><a href="http://www.strandlight.com/" target="_blank">Strand Lighting</a></li>
<li><a href="http://www.etcconnect.com/" target="_blank">ETC</a></li>
<li><a href="http://www.zero88.co.uk/" target="_blank">Zero88</a></li>
<li><a href="http://www.avolites.org.uk/" target="_blank">Avolites</a></li>
<li><a href="http://www.martin.com/" target="_blank">Martin Professional</a></li>
<li><a href="http://www.highend.com/" target="_blank">Highend Systems</a></li>
<li><a href="http://www.pulsarlight.com/" target="_blank">Pulsar Lighting</a></li>
<li><a href="http://www.robe.cz/" target="_blank">Robe Show Lighting</a></li>
<li><a href="http://www.futurelight.co.uk/" target="_blank">Futurelight</a></li>
</ul>
<h3>Sound Manufacturers</h3>
<ul>
<li><a href="http://www.soundcraft.com/" target="_blank">Soundcraft</a></li>
<li><a href="http://www.allen-heath.com/" target="_blank">Alien &amp; Heath</a> (formely AHB)</li>
<li><a href="http://www.mc2-audio.co.uk/" target="_blank">MC2 Amplifiers</a></li>
<li><a href="http://www.behringer.com/" target="_blank">Behringer</a></li>
<li><a href="http://www.global.yamaha.com/products/pa/index.html" target="_blank">Yamaha</a></li>
<li><a href="http://www.audient.co.uk/" target="_blank">Audient</a></li>
</ul>
<h3>Discussion Boards and Forums</h3>
<ul>
<li><a href="http://www.blue-room.org.uk/" target="_blank">The Blue Room</a> - A UK based privately-run forum</li>
<li><a href="http://www.lightnetwork.com/" target="_blank">The Light Network</a> - A largely US forum with a large manufacturer presence</li>
</ul>
<h3>Professional Bodies</h3>
<ul>
<li><a href="http://www.abtt.org.uk/" target="_blank">ABTT</a> - The Association of British Theatre Technicians</li>
<li><a href="http://www.plasa.org/" target="_blank">PLASA</a> - The Professional Lighting and Sound Association</li>
<li><a href="http://www.ald.org.uk/" target="_blank">ALD</a> - The Association of Lighting Designers (a UK body)</li>
<li><a href="http://www.psa.org.uk/" target="_blank">PSA</a> - The Production Services Association</li>
</ul>
<h3>Lighting Suppliers</h3>
<ul>
<li><a href="http://www.enlightenedlighting.co.uk/" target="_blank">Enlightened Lighting</a> - A Bath-based lighting hire company</li>
<li><a href="http://www.stagedepot.co.uk/?q=rb/29/31/33" target="_blank">Stage Depot</a> - A Bath-based sales company</li>
</ul>
<h3>Sound Equipment Suppliers</h3>
<ul>
<li><a href="http://www.apraudio.com/" target="_blank">APR Audio</a></li>
<li><a href="http://www.audioforum.co.uk/" target="_blank">Audio Forum</a></li>
</ul>
<h3>Other Useful Links</h3>
<ul>
<li><a href="http://www.nsdf.org.uk/" target="_blank">NSDF</a> - The National Student Drama Festival</li>
<li><a href="http://www.nlfireworks.com/" target="_blank">Northern Lights</a> - Norther Lights Fireworks Company</li>
<li><a href="http://www.modelbox.co.uk/" target="_blank">Modelbox</a> - CAD libraries and models of performance spaces</li>
</ul>
EOF
			,
			'published' => true,
			'user_id'   => $user->id,
		]);
	}
}

