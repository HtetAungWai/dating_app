<?php
session_start(); // START session at the top
include 'header.php';
include 'db.php';

// This simulates login for testing purposes
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 24; // or use any valid existing user ID
}

$loggedInUserId = $_SESSION['user_id'];

if (!$loggedInUserId) {
    echo "<p class='text-center text-danger'>Invalid session or user not logged in.</p>";
    exit;
}

$stmt = $conn->prepare("
  SELECT * FROM users 
  WHERE id != :userId 
  AND id NOT IN (
    SELECT liked_id FROM likes WHERE liker_id = :userId
    UNION
    SELECT disliked_id FROM dislikes WHERE disliker_id = :userId
  ) 
  LIMIT 1
");
$stmt->execute(['userId' => $loggedInUserId]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<section class="bg-danger text-white text-center py-5">
  <div class="container">
    <h1 class="display-4 fw-bold">Find Your Person</h1>
    <p class="lead">Swipe. Match. Connect. Love starts here 💘</p>
  </div>
</section>

<div class="container text-center mb-4">
  <form action="reset.php" method="post" onsubmit="return confirm('Are you sure you want to reset all likes and dislikes?');">
    <button type="submit" class="btn btn-warning rounded-pill">🔄 Reset Likes & Dislikes</button>
  </form>
</div>

<section class="container py-5">
  <h2 class="text-center text-danger mb-4">Nearby Matches</h2>

  <?php if ($row): ?>
  <div class="d-flex justify-content-center">
    <div class="card text-center shadow-lg p-3" style="width: 18rem;" id="profileCard" data-id="<?php echo $row['id']; ?>">
      <img src="<?php echo $row['photo']; ?>" class="rounded-circle mx-auto mt-3" style="width: 120px; height: 120px; object-fit: cover;" alt="Profile">
      <div class="card-body">
        <h5 class="card-title"><?php echo $row['name'] . ', ' . $row['age']; ?></h5>
        
      <p class="card-text">📍 <?php echo $row['distance_km']; ?> km away</p>
         <p class="card-text">"<?php echo $row['bio']; ?>"</p>
        <div class="d-flex justify-content-around mt-3">
          <button class="btn btn-secondary rounded-pill px-4 btn-dislike">👎 Dislike</button>
          <button class="btn btn-danger rounded-pill px-4 btn-like">❤️ Like</button>
        </div>
      </div>
    </div>
  </div>
  <?php else: ?>
    <p class="text-center text-muted">No profiles found.</p>
  <?php endif; ?>
</section>

<script>
function handleReaction(action) {
  const profileCard = document.getElementById('profileCard');
  if (!profileCard) return;

  const profileId = profileCard.dataset.id;
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "reaction.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  xhr.onload = function () {
    if (xhr.status == 200) {
      location.reload();
    } else {
      alert("Error saving reaction: " + xhr.responseText);
    }
  };

  xhr.send("action=" + action + "&profile_id=" + profileId);
}

document.querySelector(".btn-like")?.addEventListener("click", () => handleReaction("like"));
document.querySelector(".btn-dislike")?.addEventListener("click", () => handleReaction("dislike"));
</script>

<?php include 'footer.php'; ?>
