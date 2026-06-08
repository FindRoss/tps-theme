<?php 
/**
 * Creates a section heading.
 *
 * @param array $sectionData An array containing data for the section heading.
 *                           - 'title' (string): The title of the heading.
 *                           - 'link' (string): The URL link for the heading.
 * @return void
 */


function generateSectionHeadingHTML($sectionData = NULL) {

  // Check there is a title
  $titleOutput = '';
  if (isset($sectionData['title'])) {
    $titleOutput = '<h2 class="section-heading__title">' . $sectionData['title'] . '</h2>';
  }

  // Check there is a title and a link
  // Only output the link if the title and link exist
  $linkOutput = '';
  if (isset($sectionData['link']) AND isset($sectionData['title'])) {
    $linkOutput = '<a href="' . $sectionData['link'] . '">See all</a>';
  }

  ?>
  <div class="section-heading">
    <?php if ($titleOutput) echo $titleOutput; ?>
    <?php if ($linkOutput) echo $linkOutput; ?>
  </div>
<?php }