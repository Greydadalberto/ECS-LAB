
# 🚀 Containerized LAMP Application Deployment on Amazon ECS

This project demonstrates how to containerize and deploy a simple **LAMP** (Linux, Apache, MySQL, PHP) application to **Amazon Elastic Container Service (ECS)** using **AWS Fargate** for serverless container management.

The deployment architecture focuses on **high availability**, **scalability**, and **ease of management** using fully managed AWS services.

---

# **Project Overview**

We built a lightweight PHP web application, containerized it with Docker, stored the image in Amazon ECR, and deployed it to ECS Fargate.

This infrastructure can be extended to connect to Amazon RDS for a production-ready MySQL backend.

---

## 🧩 **Architecture Diagram**

┌────────────┐
| Client |
└─────┬──────┘
|
| HTTP request (Port 80)
▼
┌────────────┐
| ALB (Optional) |
└─────┬──────┘
|
▼
┌─────────────────────────┐
| ECS Fargate Cluster |
| ┌─────────────────────┐ |
| | ECS Service | |
| | ┌───────────────┐ | |
| | | Task (Docker) | | |
| | | Apache+PHP | | |
| | └───────────────┘ | |
| └─────────────────────┘ |
└─────────────────────────┘
|
▼
Amazon CloudWatch Logs


---

## **Steps Followed**

### 1 Prerequisites
- AWS Account (region: `eu-west-1`)
- AWS CLI installed & configured
- Docker installed
- IAM permissions for ECS, ECR, CloudWatch

---

### 2 Application & Dockerization
- Wrote a simple `index.php`:
  
## 3. Created a Dockerfile:
docker build -t ecs-lab .

## 4. Built and tested locally:
 docker run -p 8080:80 ecs-lab

## 5 Create ECR repository
```bash
aws ecr create-repository --repository-name ecs-lab --region eu-west-1

## 6 Authenticate Docker to push image
aws ecr get-login-password --region eu-west-1 | docker login --username AWS --password-stdin 537124942586.dkr.ecr.eu-west-1.amazonaws.com

## 7 Build & tag Docker image
docker build -t ecs-lab .
docker tag ecs-lab:latest 537124942586.dkr.ecr.eu-west-1.amazonaws.com/ecs-lab:latest

## 8 Push image to ECR
docker push 537124942586.dkr.ecr.eu-west-1.amazonaws.com/ecs-lab:latest

## 9 Create ECS cluster
aws ecs create-cluster --cluster-name ecs-lab-cluster --region eu-west-1


##10 Register task definition
created a json file and

aws ecs register-task-definition \
  --cli-input-json file://ecs-task-definition.json \
  --region eu-west-1


## 11 Create security group & allow HTTP
aws ec2 create-security-group \
  --group-name ecs-lab-sg \
  --description "Allow HTTP" \
  --vpc-id vpc-035e416c9b28dc627 \
  --region eu-west-1

aws ec2 authorize-security-group-ingress \
  --group-id sg-0219c2cc8113fa37e \
  --protocol tcp --port 80 --cidr 0.0.0.0/0 \
  --region eu-west-1


## 12  Create ECS service

aws ecs create-service \
  --cluster ecs-lab-cluster \
  --service-name ecs-lab-service \
  --task-definition ecs-lab-task:1 \
  --desired-count 1 \
  --launch-type FARGATE \
  --network-configuration "awsvpcConfiguration={subnets=[subnet-03306565944b60cb4,subnet-0293bd150d99280c5],securityGroups=[sg-0219c2cc8113fa37e],assignPublicIp=ENABLED}" \
  --region eu-west-1


## 13 Verify app is running
list tasks : aws ecs list-tasks --cluster ecs-lab-cluster --region eu-west-1


Describe task to get ENI:

aws ecs describe-tasks \
  --cluster ecs-lab-cluster \
  --tasks <task-arn> \
  --region eu-west-1 \
  --query "tasks[0].attachments[0].details[?name=='networkInterfaceId'].value" \
  --output text


Get public IP:
aws ec2 describe-network-interfaces \
  --network-interface-ids <eni-id> \
  --region eu-west-1


  Open http://3.253.248.102/in browser.


Delete service, task definition, cluster, security group, ECR repo:
aws ecs update-service --cluster ecs-lab-cluster --service ecs-lab-service --desired-count 0 --region eu-west-1
aws ecs delete-service --cluster ecs-lab-cluster --service ecs-lab-service --force --region eu-west-1
aws ecs deregister-task-definition --task-definition ecs-lab-task:1 --region eu-west-1
aws ecs delete-cluster --cluster ecs-lab-cluster --region eu-west-1
aws ec2 delete-security-group --group-id sg-0219c2cc8113fa37e --region eu-west-1
aws ecr delete-repository --repository-name ecs-lab --force --region eu-west-1



  Containerized LAMP app deployed on ECS with high availability.
Author: Derrick Nii Amanor Alberto-Darku (greydadalberto)


