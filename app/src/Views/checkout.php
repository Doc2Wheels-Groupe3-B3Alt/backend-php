<script src="https://js.stripe.com/v3/"></script>
<button id="checkout-button">Payer</button>

<script>
    document.getElementById("checkout-button").addEventListener("click", async () => {
        console.log("Bouton cliqué !");

        let response = await fetch("/checkout", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                action: "pay"
            })
        });

        if (!response.ok) {
            const errorText = await response.text();
            console.error("Erreur du serveur :", errorText);
            return;
        }

        const result = await response.json(); 
        console.log("Réponse du serveur :", result); 

        const stripe = Stripe("pk_test_51QukgX4GYCGhgx29uqJfWN5yxkKU8HkMkng3bs6p2Sq98HXcgturCneYuJPbwTKUZJAjjaCCsaCVsZ6CjCK2bkau002WuXklq0");
        const {
            error
        } = await stripe.redirectToCheckout({
            sessionId: result.id 
        });

        if (error) {
            console.error("Erreur lors de la redirection vers Stripe :", error);
        }
    });
</script>